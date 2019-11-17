<?php
session_start();

include_once "includes/config.php";
include_once "includes/db.php";

$pref = $pdo->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref = $pref->fetch();

if(isset($_POST['login'])) {

  if(!isset($_POST['username'])){
    header("Location:login.php?err_msg=Username not set!");
    exit();
  }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM " . ADMIN_TABLE . " WHERE " . ADMIN_USERNAME . " = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if($stmt->rowCount() == 1) {
        $stmt = $stmt->fetch();
        $hash = $stmt->{ADMIN_PASSWORD};

        $_SESSION['username'] = $username;

        if (password_verify($password, $hash)) {
            //header('Location: backend_index.php');
            header('Location: home.php');
            exit();
        } else {
            header("Location:login.php?err_msg=Invalid Password");
            exit();
        }

    }else{
        header("Location:login.php?err_msg=Invalid Username");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="/astragal/fa/js/all.js"></script> <!--load all styles -->
    <link rel="stylesheet" href="/astragal/fa/css/all.css">

    <link type="text/css" rel="stylesheet" href="/astragal/css/login_style.css">
    <title>Astra London</title>
</head>
<body>




<div class="login_parent">



        <div class="login_pic" style="background-image: url('img/uploads/pref/<?php echo $pref->{SETTINGS_PROFILE_IMAGE_LOCATION} ?>')"></div>

        <h2 class="login_title">Admin Login</h2>
<form class="login" method="post">
        <?php

        if(isset($_GET['err_msg'])){

            echo "
            <div class='alert'>$_GET[err_msg]</div>
            ";
        }
        ?>


        <div class="login_form">
            <label class="login_input_lbl" for="exampleInputName2">Username</label>
            <br>
            <input name="username" type="text" class="login_input" id="exampleInputName2" placeholder="Username">
            <br>
            <label class="login_input_lbl" for="exampleInputPassword3">Password</label>
            <br>
            <input name="password" type="password" class="login_input" id="exampleInputPassword3" placeholder="Password">
        </div>
        <br>
        <a class="login_btn" href="/astragal/home.php">Back to home</a>
        <button name="login" type="submit" class="login_btn">Sign in</button>

</div>
</body>
</html>
