<?php
include ("../../config/config.php");
include ("../classes/User.php");

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    $userId = $_POST['user_id'];
    $userObj = new User($con, $userId);

    $result = [];
    
    if($action == 'change_password') {
        $password = $_POST['password'];
        $result['success'] = $userObj->changePassword($password);
    }

    if($action == 'close_account') {
        $result['success'] = $userObj->closeAccount();
    }

    if($action == 'open_account') {
        $result['success'] = $userObj->openAccount();
    }

    if($action == 'edit_profile') {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $sex = $_POST['sex'];
        $birthday = $_POST['birthday'];

        $result['success'] = $userObj->editProfile($firstName, $lastName, $sex, $birthday);
    }

    echo json_encode($result);
}
?>