<?php
include ("../../config/config.php");
include ("../classes/User.php");
include ("../classes/Post.php");

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    $userId = $_POST['user_id'];
    $postId = $_POST['post_id'];

    $post = new Post($con, $userId);

    $response = array();

    $response['success']  = false;

    if($action == 'delete') {
        $result = $post->deletePost($postId);

        if($result) {
            $response['success']  = true;
        }
    }

    if($action == 'hide') {
        $result = $post->displayPost($postId, $action);

        if($result) {
            $response['success']  = true;
        }
    }

    if($action == 'show') {
        $result = $post->displayPost($postId, $action);

        if($result) {
            $response['success']  = true;
        }
    }

    echo json_encode($response);
}

?>