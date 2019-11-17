<?php

include_once "includes/config.php";
include_once "includes/db.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// grabbing settings
$pref = $pdo->prepare("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref->execute();
$pref = $pref->fetch();

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

// include the update functions
include_once "$dir/includes/upload.general_settings.inc.php";
include_once "$dir/includes/param_update.inc.php";

if(isset($_POST['submit']))
{
  if(isset($_FILES['pp_file'])) {
    $ppvar = upload_backend_index("pp_file", $pdo);
    // if the database is updated add success message, if post var is empty add error message
    if($ppvar == "success"){ $header_success_msgs[] = "Successfully updated profile image!"; }
  else if($ppvar != false){ $header_error_msgs[] = $ppvar; }
  }

  if(isset($_FILES['bk_file'])) {
    $bkvar = upload_backend_index("bk_file", $pdo);

    // if the database is updated add success message, if post var is empty add error message
    if($bkvar == "success"){ $header_success_msgs[] = "Successfully updated background image!"; }
    else if($bkvar != false){ $header_error_msgs[] = $bkvar; }
  }

  if(isset($_POST['main_text'])) {

    // if the database is updated add success message, if post var is empty add error message
    if(string_update($_POST['main_text'], SETTINGS_TITLE, $pdo)){ $header_success_msgs[] = "Successfully updated title!"; }
    else if(empty($_POST['main_text'])){ $header_error_msgs[] = "Error, no title!"; }
  }

  if(isset($_POST['sub_text'])) {
    if(string_update($_POST['sub_text'], SETTINGS_SUBTITLE, $pdo)){
      $header_success_msgs[] = "Successfully updated subtitle!";
    }
    else if(empty($_POST['sub_text'])){
      $header_error_msgs[] = "Error, no subtitle!";
    }
  }
  
}


include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
?>

<div class="content">

  <?php include "includes/header_msg_display.php"; ?>

  <h1 class="page-header"><i class="fas fa-wrench"></i> General Settings Menu</h1>

  <form method="post" enctype="multipart/form-data">
    <table class="form_table">
      <tr class="form-group">
        <td><h3 class="sub-header"><i class="fas fa-font"></i> Blog Title</h3><p>Give your blog a catchy cool title (or just use your name, I'm not really that bothered).</p> </td>
        <td></td>
        <td><input required class="form-control" type="text" name="main_text" minlength="3" maxlength="20" value="<?php echo $pref->{SETTINGS_TITLE}; ?>"/></td>
      </tr>

      <tr><td><hr></td><td></td><td><hr></td></tr>
      <tr class="form-group">
        <td><h3 class="sub-header"><i class="fas fa-quote-left"></i> Subtitle</h3><p>Sum up what your blog is about in a few words or a short sentence.</p> </td>
        <td></td>
        <td><input required class="form-control" type="text" name="sub_text" minlength="3" maxlength="20" value="<?php echo $pref->{SETTINGS_SUBTITLE}; ?>"/></td>
      </tr>

      <tr><td><hr></td><td></td><td><hr></td></tr>
      <tr class="form-group">
        <td><h3 class="sub-header"><i class="fas fa-image"></i> Replace Background Image (jpg, png)</h3>
          <p>You can upload a custom image here to use as the background for the landing page and title areas.</p></td>
          <td></td>
          <td><br><input id="bk_file" class="inputfile" type="file" name="bk_file"/>  <br>  <label for="bk_file">Choose a file</label></td>
        </tr>
        <tr class="form-group">
          <td></td>
          <td></td>
          <td><p class="sub-header">Current Image:</p><div class="editpost_img_preview" style="background-image: url('img/uploads/pref/<?php echo $pref->{SETTINGS_BK_IMAGE_LOCATION}; ?>');">
          </div></td>
        </tr>
        <tr><td><hr></td><td></td><td><hr></td></tr>

        <tr class="form-group">
          <td>
            <h3 class="sub-header"><i class="fas fa-user"></i> Replace Profile Image (jpg, png)</h3>
            <p>This is the image that will appear on the sidebar above the blog title.</p>
          </td>
          <td></td>
          <td><br> <input id="pp_file" class="inputfile" type="file" name="pp_file">  <br>  <label for="pp_file">Choose a file</label></td>

        </tr>

        <tr class="form-group">
          <td></td>
          <td></td>
          <td><p class="sub-header">Current Image:</p><div class="editpost_img_preview" style="background-image: url('img/uploads/pref/<?php echo $pref->{SETTINGS_PROFILE_IMAGE_LOCATION}; ?>');"></div></td>
        </tr>

        <tr class="form-group">
          <td>
            <br>
          </td>
        </tr>

        <td>
          <div class="form-group">
            <button class="btn" name="submit" type="submit">Update</button>
          </div>
        </td>
      </tr>
    </table>

    <br>

  </form>
</div>
</body>
</html>
