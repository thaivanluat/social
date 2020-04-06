<?php
if(isset($_POST['submit'])) {
    // Get data;
    $firstName = handleInputText($_POST['first_name']);
    $lastName = handleInputText($_POST['last_name']);
    $email = handleInputText($_POST['email']);
    $password = trim($_POST['pass']);
    $confirmPassword = trim($_POST['confirm_pass']);
    $sex = $_POST['sex'];
    $birthDay = $_POST['birthday_day'] == 0 ? date("d") : $_POST['birthday_day'];
    $birthMonth = $_POST['birthday_month'] == 0 ? date("m") : $_POST['birthday_month'];
    $birthYear = $_POST['birthday_year'] == 0 ? date("Y") : $_POST['birthday_year'];

    $insertDBBirthdate = date('Y/m/d', strtotime($birthDay."-".$birthMonth."-".$birthYear));

    if($password != $confirmPassword) {
        $_SESSION['register_error_message'] = "Có lỗi xảy ra trong quá trình đăng ký. <a href='register.php'>Thử lại</a><br>";
        header("Location: register.php");
        exit();
    }
    else {
        $password = md5($password);
    }

    $createdTime = date('Y-m-d H:i:s');

    // Profile picture assignment 
    if($sex == 'female') {
        $avatar = "assets/images/default_avatar/female.png";
    }
    else {
        $avatar = "assets/images/default_avatar/male.png";
    }

    $sql = "
    INSERT INTO user (
        first_name,
        last_name,
        email,
        password,
        created_time,
        avatar,
        sex,
        birthday
    )
    VALUES (
        '{$firstName}',
        '{$lastName}',
        '{$email}',
        '{$password}',
        '{$createdTime}',
        '{$avatar}',
        '{$sex}', 
        '{$insertDBBirthdate}'
    );
";

    $addUser = mysqli_query($con, $sql);

    // Thông báo quá trình lưu
    if ($addUser) {
        $_SESSION['success_message'] =  "Register successfully";
        header("Location: register.php");
        exit();
    }
    else {
        $_SESSION['register_error_message'] = "Có lỗi xảy ra trong quá trình đăng ký. <a href='register.php'>Thử lại</a><br>";
        header("Location: register.php");
        // $_SESSION['register_error_message_sql'] = mysqli_errno($con);
        exit();
    } 
}


?>