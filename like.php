<?php
    require 'config/config.php';

    if(isset($_GET['type']) && isset($_GET['post_id']) && isset($_GET['user_id'])) {
        $type = $_GET['type'];
        $postId = $_GET['post_id'];
        $userId = $_GET['user_id'];
        if($type == 'like') {
            $sql = "INSERT INTO likes(post_id, user_id) VALUES ('$postId', '$userId')";
        }
        else if($type == 'unlike') {
            $sql = "DELETE FROM likes WHERE post_id = '$postId' AND user_id='$userId'";
        }

        $query = mysqli_query($con, $sql);

        if($query) {
            $result = true;
        }
        else {
            $result = false;
        }
    }
    else {
        $result =  false;
    }

    echo $result;
?>
