<?php
//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

// include header
$title = "About";
include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
 ?>

<div class="content">

  <h1><i class="fas fa-question-circle"></i> About</h1>
  <p><?php echo "$APPNAME $APPVERSION by $APPAUTHOR"; ?></p>
  <a href="<?php echo "$APPAUTHORADDRESS"; ?>"><?php echo "$APPAUTHORADDRESS"; ?></a>

</div>

</body>
</html>
