<?php
include ("../../config/config.php");
include ("../classes/User.php");
include ("../classes/Post.php");

$posts = new Post($con, $_POST['user_id']);
$posts->loadProfilePost($_POST['user_id']);
?>