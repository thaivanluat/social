<?php
include ("../../config/config.php");
include ("../classes/User.php");

if(isset($_POST['response'])) {
    $response = $_POST['response'];
    $fromUserId = $_POST['to_user_id'];
    $toUserId = $_POST['from_user_id']; 

    $fromUserObj = new User($con, $fromUserId);
    $toUserObj = new User($con, $toUserId);

    if($response = 'accept') {
        $fromUserObj->acceptFriendRequest($toUserId);
        $toUserObj->acceptFriendRequest($fromUserId);
    }
    else if($response = 'ignore') {
        $fromUserObj->cancelFriendRequest($toUserId); 
    }

    echo json_encode(array(
        'success' => true
    ));
}


?>