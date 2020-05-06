<?php
include ("../../config/config.php");
include ("../classes/User.php");

if(isset($_POST['image'])) {
    $userId = $_SESSION['user']['id'];
    $target_dir = ROOT_DIR."/storage/user_avatar/";

    // Get current user
    $user = new User($con, $userId);

    $data = $_POST['image'];

    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    $saveImageName = $target_dir.$userId.".png";

    $result = file_put_contents($saveImageName, $data);

    $response = [];

    if($result) {
        $dbImageName = "storage/user_avatar/".$userId.".png";
        $user->changeAvatar($dbImageName);
        $response['success'] = true;
        $response['image'] = "<img src='storage/user_avatar/".$userId.".png?t=".time()."'>";
    }
    else {
        $response['success'] = false;
    }

    echo json_encode($response);
}
?>