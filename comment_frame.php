<?php
    header('Cache-Control: no cache'); 
    require ('config/config.php');
    include "includes/classes/User.php";
    include "includes/classes/Post.php";    
    if(isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $userId = $user['id'];
    }
    else {
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <style>
    * {
        font-size: 12px;
        font-family: Arial, Helvetica, Sans-serif
    }
    </style>
</head>
<body>
    <script>
        function toogle() {
            var element = document.getElementById("comment_section");

            if(element.style.display == "block") {
                element.style.display = "none";
            }
            else {
                element.style.display = "block";
            }
        }
    </script>

    <?php
        // Get id of post
        if(isset($_GET['post_id'])) {
            $postId = $_GET['post_id'];
        }

        $userQuery = mysqli_query($con, "SELECT post_user, related_user FROM post WHERE id='$postId'");
        $row = mysqli_fetch_array($userQuery);

        if(isset($_POST['postComment'.$postId])) {
            $postBody = $_POST['post_body'];
            $postBody = strip_tags($postBody); // remove html tag
            $postBody = mysqli_real_escape_string($con, $postBody);

            $postTime = date("Y-m-d H:i:s");
            $sql = "INSERT INTO `comment` (body, post_id, user_id, added_time) VALUES ('$postBody', '$postId', '$userId', '$postTime')";
            $insertQuery = mysqli_query($con, $sql);
            if($insertQuery) {
                echo "<p>Comment posted !</p>";
            }
            else {
                echo "<p>Error has occurred !</p>";
            }
        }
    ?>

    <form action="comment_frame.php?post_id=<?php echo $postId; ?>" id="comment_form" name="postComment<?php echo $postId; ?>" method="POST">
        <textarea name="post_body" class="comment_text"></textarea>
        <input type="submit" name="postComment<?php echo $postId; ?>" value="Post" class="comment_button" disabled>
    </form>

    <!-- Load comment -->
    <?php
        $commentList = mysqli_query($con, "SELECT * FROM comment WHERE post_id='$postId' ORDER BY id ASC");
        $count = mysqli_num_rows($commentList);

        if($count != 0) {
            while($comment = mysqli_fetch_array($commentList)) {
                $commentBody = $comment['body'];
                $commentBy = $comment['user_id'];
                $dateAdded = $comment['added_time'];

                $timeMessage = getDiffTime($dateAdded);
                $userObj = new User($con, $commentBy);
                ?>
                <div class="comment_section">
                    <div class="comment_image">
                        <a href="<?php echo $commentBy; ?>" target="_parent"><img class="comment_user_img" src="<?php echo $userObj->getProfilePic(); ?>" title="<?php echo $userObj->getFirstAndLastName(); ?>" style="float: left;"></a>
                    </div>
                    <div class="comment_content">
                        <a href="<?php echo $commentBy; ?>" target="_parent"><b><?php echo $userObj->getFirstAndLastName(); ?></b></a>
                        &nbsp&nbsp&nbsp&nbsp 
                        <span style="color: #5a5a59; float: right; padding-right: 5px;"><?php echo $timeMessage; ?></span>
                        <br>
                        <p style="margin: 5px 0px;"><?php echo $commentBody; ?></p>    
                    </div>                 
                </div>
                <?php
            }
        }
    ?>
</body>
    <script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script>
        $('.comment_text').on('keyup',function() {
            let postTextValue = $('.comment_text').val();

            postTextValue =  $.trim(postTextValue);
            if(postTextValue != '') {
                $('.comment_button').attr('disabled', false);
            }
            else {
                $('.comment_button').attr('disabled', true);
            }
        });
    </script>
</html>