<?php
include ("../../config/config.php");
include ("../classes/User.php");
include ("../classes/Post.php");

if(isset($_POST['post_body'])) {
    $postBody = $_POST['post_body'];
    $toUser = $_POST['to_user_id'];
    $fromUser = $_POST['from_user_id'];

    $post = new Post($con, $fromUser);
    $post->submitPost($postBody, $toUser);

    echo json_encode(array(
        'success' => true
    ));
}
?>