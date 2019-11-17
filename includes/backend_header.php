<?php

session_start();
include_once("includes/config.php");
include_once("includes/db.php");

if(!isset($_SESSION['username'])){

    header("Location:login.php?err_msg=You need to log in to see this!");
    exit();

}

// if title not set, set default
if(!$title) {$title = "Gallery Backend";}


$pref = $pdo->prepare("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref->execute();
$pref = $pref->fetch();


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script defer src="/fa/js/all.js"></script> <!--load all styles -->
    <link rel="icon" href="img/uploads/pref/<?php echo $pref->{SETTINGS_PROFILE_IMAGE_LOCATION}; ?>">
    <link rel="stylesheet" href="/fa/css/all.css">
    <link rel="stylesheet" href="/css/backend_styles.css">
    <title><?php echo "$title"; ?></title>
    <!-- Latest compiled and minified CSS -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous">
    </script>
    <script type="text/javascript">
    function burgerMenuShowHide() {
      // show/hide menu
      var ico = document.getElementById("burgerIco");
      var bl1 = document.getElementById("bl1");
      var bl2 = document.getElementById("bl2");
      var bl3 = document.getElementById("bl3");

      var Smenu = document.getElementById("sMenu");
      var SmenuClear = document.getElementById("sMenuClear");

      if (ico.className === "burgerMenu") {
        ico.className += "Closed";

        bl1.className = "burgerLine";
        bl2.className = "burgerLine";
        bl3.className = "burgerLine";

        sMenu.className += "Closed";
        sMenuClear.className += "Closed";
      }
      // open
      else {
        ico.className = "burgerMenu";

        bl1.className = "bl1";
        bl2.className = "bl2";
        bl3.className = "bl3";

        sMenu.className = "sidebar";
        sMenuClear.className = "sidebar-clear";
      }
    }
    </script>
</head>
<body>
<div class="topBar">
    <button type="button" name="burgerMenu" class="burgerMenuClosed" id="burgerIco" onclick="burgerMenuShowHide()">
      <div class="burgerLine" id="bl1"></div>
        <div class="burgerLine" id="bl2"></div>
      <div class="burgerLine" id="bl3"></div>
    </button>
    <span class="title">Gallery Content Management System</span>

    <a class="logout" href="home.php">Back to Frontend</a>
    <a class="logout" href="logout.php">Logout</a>

</div>

<div class="topBar-clear"></div>
