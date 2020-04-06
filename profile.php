<?php
include "includes/header.php";
include "includes/classes/User.php";
include "includes/classes/Post.php";

if(isset($_GET['uid'])) {
    $userIdParam = $_GET['uid'];

    // Get current user
    $user = new User($con, $userId);

    $sql = "SELECT * FROM user WHERE id = '$userIdParam'";
    $query = mysqli_query($con, $sql);
    if(mysqli_num_rows($query) > 0) {
        $userData = mysqli_fetch_array($query);

        $numFriend = (substr_count($userData['friend_array'], ",")) + 1;
    }
    else {
        header("Location: user_not_found.php");
    }
}

try {
    if(isset($_SERVER['HTTP_REFERER']))
        $referer = $_SERVER['HTTP_REFERER'];
}
catch(Exception $e) {
    $referer = "index.php";
}

if(isset($_POST['remove_friend'])) {
    $user->removeFriend($userIdParam);

    $userProfile = new User($con, $userIdParam);
    $userProfile->removeFriend($userId);

    header("Location: $referer");
}

if(isset($_POST['cancel_friend_request'])) {
    $user->cancelFriendRequest($userIdParam);

    header("Location: $referer");
}

if(isset($_POST['send_friend_request'])) {
    $user->sendFriendRequest($userIdParam);

    header("Location: $referer");
}

?>
        <style type="text/css">
            .wrapper {
                margin-left: 0px;
                padding-left: 0px;
            }

            #post_button {
                background: linear-gradient(-135deg, #c850c0, #4158d0);
            }

            #post_button:disabled {
                background-color: #8383836b;
            }

            .btn {
                cursor: pointer;
            }
        </style>
        <div class="profile_left">
            <img class="avatar" src="<?php echo $userData['avatar']?>">
                <p class="profile_name"><?php echo $userData['first_name']." ".$userData['last_name']; ?></p>
            <div class="profile_info">
                <p><?php echo "Posts: ". $userData['num_posts']; ?></p>
                <p><?php echo "Friends: ". $numFriend; ?></p>
                <p><?php 
                    $countMutualFriend =  $user->countMutualFriend($userIdParam); 
                    if($countMutualFriend > 0 && $userId != $userIdParam)
                    echo "Mutual friends: ". $countMutualFriend; 
                ?></p>
            </div>

            <form action="<?php echo $userIdParam; ?>" method="POST" style="text-align: center;">
                <?php
                    $profileUserObj = new User($con, $userIdParam);
                    if($profileUserObj->isClosed()) {
                        header("Location: user_closed.php");
                    }

                    $loggedUserObj = new User($con, $userId);
                    if($userId != $userIdParam) {
                        if($loggedUserObj->isFriend($userIdParam)) {
                            echo '<input type="submit" name="remove_friend" class="danger-friend-request" value="Remove Friend">';
                        }
                        else if($loggedUserObj->didReceiveFriendRequest($userIdParam)) {
                            echo '<input type="submit" name="cancel_friend_request" class="warning-friend-request" value="Cancel Friend Request">';
                        }
                        else {
                            echo '<input type="submit" name="send_friend_request" class="primary-friend-request" value="Send Friend Request">';
                        }
                    }
                    else {
                        echo '<a class="btn btn-info" href="edit_profile.php">Edit profile</a>';
                    }
                ?>
            </form>

            <div style="text-align: center; margin-top: 10px;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#post_form">
                    Post something
                </button>  
            </div>       
        </div>

        <div class="main_column column">
            <div class="posts_area">
                <?php
                    $post = new Post($con, $userIdParam);
                    $post->loadProfilePost($userIdParam);
                ?>
            </div>
            <div class="loading_area">
                <img src="assets/images/icons/loading.gif" id="loader">
                <input type='hidden' class='loadMore' value='true'><p class="noMorePosts" style='text-align: center; display: none;'> No more posts to show! </p>
            </div>
            <input type="hidden" id="user_id" value="<?php echo $userIdParam; ?>">
            <input type="hidden" id="page_no" value="1">
        </div>

        <div class="main-loader" style="display: none;">
            <img src="assets/images/icons/main-loading.gif" style="height: 60px;" class="loader">
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postForm" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="postForm">Post something!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <?php
                    if($userId != $userIdParam)
                    echo "<p style='font-size: 12px; color: #4c4949'>This will appear on the user's profile page and also their newfeed for your friend to see</p>";
                    else
                    echo "<p style='font-size: 12px; color: #4c4949'>This will appear on your profile page and also your friend newfeed</p>"
                ?>    
                    <form class="profile_post" action="" method="POST">
                        <div class="form-group">
                            <textarea class="form-control" name="post_text" id="post_text"></textarea>
                            <input type="hidden" name="user_from" value="<?php echo $userId; ?>">
                            <input type="hidden" name="user_to" value="<?php echo $userIdParam; ?>">
                        </div>                
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="post_button" disabled>Post</button>
                </div>
            </div>
        </div>
    </div>
    <!-- JS -->
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/js/profile.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>