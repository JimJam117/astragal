<?php session_start();
include_once ("includes/config.php");
include_once ("includes/db.php");

$pref = $pdo->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref = $pref->fetch();

if(!$title) {$title = $pref->{SETTINGS_TITLE};}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="fa/css/all.css">
  <link rel="icon" href="img/uploads/pref/<?php echo $pref->{SETTINGS_PROFILE_IMAGE_LOCATION}; ?>">
  <script src="includes/jquery.min.js" charset="utf-8"></script>
   <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

  <script type="text/javascript">
  function copyToClipboard() {
    var copyText = document.getElementById("textToCopy");

    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
  }
  function emailDisplay(){
    var email_element = document.getElementById("tooltiptext_email");
    email_element.classList.toggle("tooltiptext_reveal");
  }
  </script>
  <script type="text/javascript">
  function burgerMenuShowHide() {
    // show/hide menu
    var ico = document.getElementById("bMenuIcon");
    var text = document.getElementById("bMenuText");

    var Main = document.getElementById("mainContent");
    var Smenu = document.getElementById("sMenu");
    var SmenuClear = document.getElementById("sMenuClear");

    if (ico.className === "fas fa-angle-left") {
      ico.className = "fas fa-angle-right";
      text.className = "bMenuTextShowMessage";

      Main.className += " main_content_full";


      sMenu.className += " top_band_closed";
      sMenuClear.className += " top_band_fix_closed";
    }
    // open
    else {
      ico.className = "fas fa-angle-left";
      text.className = "bMenuTextHideMessage";

      Main.className = "main_content";
      sMenu.className = "top_band";
      sMenuClear.className = "top_band_fix";
    }
  }
  </script>

  <link type="text/css" rel="stylesheet" href="css/style.css">
  <title><?php echo "$title"; ?></title>
</head>

<body style="background-image: url('img/uploads/pref/<?php echo $pref->{SETTINGS_BK_IMAGE_LOCATION}; ?>');">

  <div id="sMenu" class="top_band top_band_closed">
    <div class="band_top_section">
      <button type="button" name="burgerMenu" class="burgerMenuClosed" id="burgerIco" onclick="burgerMenuShowHide()">
        <i id="bMenuIcon" class="fas fa-angle-right"></i>
        <span id="bMenuText" class="bMenuTextShowMessage" >Hide</span>
      </button>

      <div class="profile_image" style="background-image: url('img/uploads/pref/<?php echo $pref->{SETTINGS_PROFILE_IMAGE_LOCATION}; ?>');" alt="<?php echo $pref->{SETTINGS_TITLE}; ?>"></div>
      <h2 class="title"><?php echo $pref->{SETTINGS_TITLE}; ?></h2>
      <h3 class="subtitle"><?php echo $pref->{SETTINGS_SUBTITLE};?></h3>
      <?php // Admin panel link
      if(isset($_SESSION['username'])){
        echo "<a class='admin_panel_link' href='backend_index.php'>Admin Panel</a>";
      } ?>
    </div>

    <!-- SEARCHBAR -->

    <div class="searchBar">
      <form class="searchForm" method="get" action="results.php">
        <button type="submit" class="searchButton">  <i class="fas fa-search"></i> </button>
        <input type="text" name="search" class="searchInput" id="exampleInputName2" placeholder="Search...">

      </form>

    </div>


    <ul class="main_links">
      <li class="main_link_header_li"><a class="main_link_header" href="/home.php">HOME</a></li>
      <li class="main_link_header_li"><a class="main_link_header" href="/list_posts.php">POSTS</a></li>
      <li class="main_link_header_li"><a class="main_link_header" href="/list_albums.php">ALBUMS</a></li>
    </ul>



  </div>
  <div id="sMenuClear" class="top_band_fix top_band_fix_closed"></div>

  <div id="mainContent" class="main_content">
