<?php

if(isset($_POST['submit'])) {
    $email = handleInputText($_POST['email']);  // sanitize email

    $password = md5(trim($_POST['pass']));

    $sql = "SELECT email, password FROM user WHERE email='$email'";

    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) == 0) {
        $error = "Email not exists";
        $_SESSION['error_message'] = $error;
        header("Location: login.php");
        exit();
    }

    $row = mysqli_fetch_array($query);

    if($password != $row['password']) {
        $error = "Password is not correct";
        $_SESSION['error_message'] = $error;
        header("Location: login.php");
        exit();
    }


    $sql = "SELECT * FROM user WHERE email='$email'";
    $query = mysqli_query($con, $sql);
    $user = mysqli_fetch_array($query);

    $_SESSION['user'] = $user;
    header("Location: index.php");
    exit();
}
?>