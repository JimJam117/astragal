<?php
//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

// include header
$title = "Ooopsie";

$err_msg = false;
if(isset($_GET['err_msg'])) {
  $err_msg = $_GET['err_msg'];
}

include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";

?>

<div class="content">

  <img src="404.png" alt="">
  <h1>Error, page not found</h1>
  <?php if($err_msg) {echo "<h3>Error Message: $err_msg</h3>";} ?>

</div>

</body>
</html>
