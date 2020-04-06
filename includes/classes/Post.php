<?php

class Post {
    private $userObj;
    private $con;

    public function __construct($con, $userId) {
        $this->con = $con;
        $this->userObj = new User($con, $userId);
    }

    public function submitPost($body, $relatedUser) {
        $body = strip_tags($body); // remove html tag
        $body = mysqli_real_escape_string($this->con, $body);
        $checkEmpty = preg_replace('/\s+/', '', $body);
        
        if(!empty($checkEmpty)) {
            $postedTime = date("Y-m-d H:i:s"); // Get current date
            $postUser = $this->userObj->getId();

            if($postUser == $relatedUser) {
                $userTo = NULL;
            }

            $sql = "INSERT INTO post (body, post_user, related_user, posted_time) 
            VALUES ('$body', '$postUser', '$relatedUser', '$postedTime')";
            // Insert post
            $query = mysqli_query($this->con, $sql);

            // Update num post
            $sql = "UPDATE user SET num_posts = num_posts + 1 WHERE id='$postUser'";
            $query = mysqli_query($this->con, $sql);
        }
    }

    public function loadPost() {
        if(isset($_POST['page_no'])){
            $pageNo = $_POST['page_no'];
        }
        else {
            $pageNo = 1;
        }

        $numRecordPerPage = 10;
        $offset = ($pageNo-1) * $numRecordPerPage;

        $friendListArray = $this->userObj->getFriendListText();

        $userId = $this->userObj->getId();

        $str = ""; // String to return
        $data_query =  mysqli_query($this->con, "SELECT * 
                                                FROM post 
                                                WHERE `status` = 'show' AND (post_user IN ($friendListArray) OR post_user = '$userId')
                                                ORDER BY id DESC LIMIT $offset, $numRecordPerPage");


        if(mysqli_num_rows($data_query) > 0) {

            while($row = mysqli_fetch_array($data_query)) {
                $addTime =  $row['posted_time'];
                $postId = $row['id'];

                // Add text if post relate to other user
                if($row['post_user'] != $row['related_user']) {
                    $relatedUserObj = new User($this->con, $row['related_user']);
                    $relatedUserName = $relatedUserObj->getFirstAndLastName();
                    $relatedUser = "to <a href='". $row['related_user']."'>".$relatedUserName."</a>";
                }
                else {
                    $relatedUser = ""; 
                }

                // Check user who posted has their account closed
                $postUser = new User($this->con, $row['post_user']);
                $profilePic = $postUser->getProfilePic();
                $postUserId = $row['post_user'];
                $body = $row['body'];
                $postUserName = $postUser->getFirstAndLastName();

                if($postUser->isClosed()) {
                    continue;
                }

                if($userId == $postUserId) {
                    $actionButton = "
                        <a class='delete_post dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='cursor: pointer'>
                            <i class='fa fa-menu' aria-hidden='true'></i>
                        </a>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item post_action btnHidePost' style='cursor: pointer'>Hide</a>
                            <a class='dropdown-item post_action btnDeletePost' style='cursor: pointer'>Delete</a>
                        </div>
                    ";
                }
                else {
                    $actionButton = "";
                }
                
                ?>
                <script>
                    function toggle<?php echo $postId; ?>() {
                        var element = document.getElementById("toggleComment<?php echo $postId; ?>");

                        if(element.style.display == "block") {
                            element.style.display = "none";
                        }
                        else {
                            element.style.display = "block";
                        }
                    }
                </script>
                <?php
                $timeMessage = getDiffTime($addTime);

                $commentCountQuery = mysqli_query($this->con, "SELECT id FROM comment WHERE post_id = '$postId'");
                $commentCount = mysqli_num_rows($commentCountQuery); 

                $likeCountQuery = mysqli_query($this->con, "SELECT post_id FROM likes WHERE post_id = '$postId'");
                $likeCount = mysqli_num_rows($likeCountQuery);

                
                $isLikeQuery =  mysqli_query($this->con, "SELECT user_id FROM likes WHERE post_id = '$postId' AND user_id = '$userId'");
                $isLike = mysqli_num_rows($isLikeQuery) > 0 ? true : false;

                $str .= "   <div class='status_post'>
                                <input type='hidden' class='post_id' value='$postId'>
                                <div class='post_profile_pic'>
                                    <a href='$postUserId'><img src='$profilePic' width='50'></a>
                                </div>

                                <div class='posted_by' style='color:#ACACAC;'>
                                    <a href='$postUserId'> $postUserName </a> $relatedUser &nbsp;&nbsp;&nbsp;&nbsp;$timeMessage
                                    $actionButton
                                </div>
                                <div id='post_body'>
                                    $body
                                    <br><br>
                                </div>
                                <div class='likes'>
                                    <span class='like-count'>$likeCount</span>&nbsp<i class='fa fa-thumbs-up like-button ".($isLike ? "active" : "")."' aria-hidden='true'></i>
                                </div>
                                <div class='comment_number' onClick='javascript:toggle$postId()'>
                                    <p>Comment($commentCount)</p>
                                </div>
                                <hr>
                            </div>
                            <div class='post_comment' id='toggleComment$postId' style='display: none;'>
                                <iframe src='comment_frame.php?post_id=$postId' class='comment_iframe' frameborder='0'></iframe>
                            </div>
                            ";
             
            }
            echo $str;
        }
    }

    public function loadProfilePost($userId) {
        if(isset($_POST['page_no'])){
            $pageNo = $_POST['page_no'];
        }
        else {
            $pageNo = 1;
        }

        $numRecordPerPage = 10;
        $offset = ($pageNo-1) * $numRecordPerPage;

        $friendListArray = $this->userObj->getFriendListText();

        $str = ""; // String to return
        $data_query =  mysqli_query($this->con, "SELECT * 
                                                FROM post 
                                                WHERE (post_user = '$userId' OR related_user = '$userId')
                                                ORDER BY id DESC LIMIT $offset, $numRecordPerPage");


        if(mysqli_num_rows($data_query) > 0) {

            while($row = mysqli_fetch_array($data_query)) {
                $addTime =  $row['posted_time'];
                $postId = $row['id'];

                // Add text if post relate to other user
                if($row['post_user'] != $row['related_user']) {
                    $relatedUserObj = new User($this->con, $row['related_user']);
                    $relatedUserName = $relatedUserObj->getFirstAndLastName();
                    $relatedUser = "to <a href='". $row['related_user']."'>".$relatedUserName."</a>";
                }
                else {
                    $relatedUser = ""; 
                }

                // Check user who posted has their account closed
                $postUser = new User($this->con, $row['post_user']);
                $profilePic = $postUser->getProfilePic();
                $postUserId = $row['post_user'];
                $body = $row['body'];
                $postUserName = $postUser->getFirstAndLastName();

                if($postUser->isClosed()) {
                    continue;
                }

                $isHide = $this->isHidePost($postId);

                if($userId == $postUserId) {
                    $actionButton = "
                        <a class='delete_post dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='cursor: pointer'>
                            <i class='fa fa-menu' aria-hidden='true'></i>
                        </a>
                        <div class='dropdown-menu'>".($isHide ? 
                        "<a class='dropdown-item post_action btnShowPost' style='cursor: pointer'>Show</a>" : 
                        "<a class='dropdown-item post_action btnHidePost' style='cursor: pointer'>Hide</a>")."
                            <a class='dropdown-item post_action btnDeletePost' style='cursor: pointer'>Delete</a>
                        </div>
                    ";
                }
                else {
                    $actionButton = "";
                }
                
                ?>
                <script>
                    function toggle<?php echo $postId; ?>() {
                        var element = document.getElementById("toggleComment<?php echo $postId; ?>");

                        if(element.style.display == "block") {
                            element.style.display = "none";
                        }
                        else {
                            element.style.display = "block";
                        }
                    }
                </script>
                <?php
                $timeMessage = getDiffTime($addTime);

                $commentCountQuery = mysqli_query($this->con, "SELECT id FROM comment WHERE post_id = '$postId'");
                $commentCount = mysqli_num_rows($commentCountQuery); 

                $likeCountQuery = mysqli_query($this->con, "SELECT post_id FROM likes WHERE post_id = '$postId'");
                $likeCount = mysqli_num_rows($likeCountQuery);

                
                $isLikeQuery =  mysqli_query($this->con, "SELECT user_id FROM likes WHERE post_id = '$postId' AND user_id = '$userId'");
                $isLike = mysqli_num_rows($isLikeQuery) > 0 ? true : false;

                $str .= "   <div class='status_post'>
                                <input type='hidden' class='post_id' value='$postId'>
                                <div class='post_profile_pic'>
                                    <a href='$postUserId'><img src='$profilePic' width='50'></a>
                                </div>

                                <div class='posted_by' style='color:#ACACAC;'>
                                    <a href='$postUserId'> $postUserName </a> $relatedUser &nbsp;&nbsp;&nbsp;&nbsp;$timeMessage
                                    ".($isHide ? "<i class='fa fa-eye-slash ' aria-hidden='true' title='This post is hide'></i>" : "")."
                                    $actionButton
                                </div>
                                <div id='post_body'>
                                    $body
                                    <br><br>
                                </div>
                                <div class='likes'>
                                    <span class='like-count'>$likeCount</span>&nbsp<i class='fa fa-thumbs-up like-button ".($isLike ? "active" : "")."' aria-hidden='true'></i>
                                </div>
                                <div class='comment_number' onClick='javascript:toggle$postId()'>
                                    <p>Comment($commentCount)</p>
                                </div>
                                <hr>
                            </div>
                            <div class='post_comment' id='toggleComment$postId' style='display: none;'>
                                <iframe src='comment_frame.php?post_id=$postId' class='comment_iframe' frameborder='0'></iframe>
                            </div>
                            ";
             
            }
            echo $str;
        }
    }

    public function deletePost($postId) {
        $sql = "DELETE FROM post WHERE id = '$postId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function hidePost($postId) {
        $sql = "UPDATE post SET status = 'hide' WHERE id = '$postId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function displayPost($postId, $action) {
        $sql = "UPDATE post SET status = '$action' WHERE id = '$postId'";
        $query = mysqli_query($this->con, $sql);

        if($query) {
            return true;
        }

        return false;
    }

    public function isHidePost($postId) {
        $query = mysqli_query($this->con, "SELECT status FROM post WHERE id='$postId'");
        $postData  = mysqli_fetch_array($query);

        $isHide = true;

        if($postData['status'] == 'show') {
            $isHide = false;
        }

        return $isHide;
    }
}