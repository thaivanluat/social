<?php
header('Cache-Control: no cache'); 
require ('config/config.php');
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
    <title>MyDairy</title>

    <base href="" target="_blank">
    <!-- CSS -->
    <link rel="icon" type="image/png" href="assets/images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <a href="index.php">MyDairy</a>
        </div>

        <nav>
            <a href="index.php" title="Home"><i class="fa fa-home" aria-hidden="true"></i></a>
            <a href="<?php echo $userId; ?>" title="Profile"><i class="fa fa-user" aria-hidden="true"></i></a>
            <a href="friend_request.php" title="Friend Request"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
            <a href="" title="Message"><i class="fa fa-comments" aria-hidden="true"></i></a>
            <a href="logout.php" title="Logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </nav>
    </div>

    <div class="wrapper">
