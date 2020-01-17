<?php

//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

// if post is set
if(isset($_GET['post'])) {
  $stmt = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_ID . "= :id");
  $stmt->bindParam(':id', $_GET['post'], PDO::PARAM_INT);
  $stmt->execute();

  // if there are no posts
  if($stmt->rowCount() <= 0) {
    header("Location:404.php?err_msg=Error, post not found :(");
    exit();
  }
  // if there is a post, fetch it
  else{
    $row = $stmt->fetch();
    $title = $row->{POST_TITLE};
  }
}

// if post value is not given in url, exit
else{
  header("Location:404.php?err_msg=Error, post not defined :(");
  exit();
}

// include header
include_once ("includes/header.php");

//main content
?>

<!--MAIN TITLE-->
<div class="title_area">
  <h1> <?php echo($row->{POST_TITLE}); ?> </h1>
</div>

<div class="single">
  <div class="single_left">

    <!--IMAGE-->
    <div class="single_image_container">
      <img class="single_image" src="img/uploads/<?php echo($row->{POST_FILENAME});?>" alt="<?php echo($row->{POST_TITLE});?>">
    </div>

    <!--LINK BUTTONS-->
    <div class="single_links">
      <a class="single_download" href="img/uploads/<?php echo($row->{POST_FILENAME});?>" download><i class="fas fa-download"></i> Download</a>
      <a class="single_viewfull" href="img/uploads/<?php echo($row->{POST_FILENAME});?>" target="_blank"><i class="far fa-image"></i> View Full Size</a>

      <!--ABLUM BUTTON-->
      <?php if ($row->{POST_CATEGORY_ID} != 0) { ?>
        <a class="single_viewalbum" href="album_single.php?post=<?php echo($row->{POST_CATEGORY_ID});?>"><i class="far fa-images"></i> View Album</a>
      <?php } ?>

      <!--BACK BUTTON-->
      <script> document.write('<a class="single_goback" href="' + document.referrer + '"><i class="fas fa-arrow-left"></i> Go Back</a>'); </script>
    </div>

  </div>

  <!--RIGHT SECTION-->
  <div class="single_right">
    <!--TITLE-->
    <h2> <?php echo($row->{POST_TITLE}); ?> </h2>

    <!--ALBUM-->
    <p class="detail">
      <?php // display album name

      // if there is an album
      if ($row->Gal_This_Post_Album_ID != 0) {
        // Find the album name and echo it
        $cat_stmt = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE " . CATEGORY_ID . "= :cat_id");
        $cat_stmt->bindParam(':cat_id', $row->{POST_CATEGORY_ID}, PDO::PARAM_INT);
        $cat_stmt->execute();
        $cat_row = $cat_stmt->fetch();
        
        echo(' This post is a part of the album
        <a href="album_single.php?post=' . $cat_row->{CATEGORY_ID} . '">' .
            $cat_row->{CATEGORY_TITLE} .
        '</a>');
      }
      ?>
    </p>

    <!--DESCRIPTION-->
    <div class="single_desc"> <?php echo($row->{POST_DESCRIPTION}); ?> </div>

  </div>
</div>


<div class="footer" style="background: white;">

  <hr>
  <br>
  <?php

  $pref = $pdo->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
  $pref = $pref->fetch();

  $EmailIsDisplaying = true;
  if(empty($pref->{SETTINGS_EMAIL}) || $pref->{SETTINGS_IS_EMAIL_ENABLED} == "0"){ $EmailIsDisplaying = false; }
  else{ $EmailIsDisplaying = true; }


  if($EmailIsDisplaying) {
    echo $pref->{SETTINGS_EMAIL};
    echo " <br><br> <a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
  }
  else {
    echo "<a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
  }
  ?>

</div>

</div> <!--MAIN CONTENT END-->

</body>
</html>

