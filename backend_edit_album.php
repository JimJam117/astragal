<?php
include_once "includes/config.php";
include_once "includes/db.php";

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

include_once "includes/upload.inc.php";

// grab the id
if(isset($_GET['post'])) {$id = $_GET['post'];}
else {
  header("location: backend_404.php?err_msg=Error, the id in the url given does not match any of the albums!");
  exit();
}

// grabbing current to compare if there are any changes
$stmt = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE " . CATEGORY_ID . " = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$current = $stmt->fetch();


if(isset($_POST['submit'])){
  if(isset($_FILES['album_file']) && $_FILES['album_file']['size'] != 0) {
    $post_var = upload_update_img("album_file", $pdo, $id);

    // if the database is updated add success message, if post var is empty add error message
    if($post_var == "success"){ $header_success_msgs[] = "Successfully updated album cover image!"; }
    else if($post_var != false){ $header_error_msgs[] = $post_var; }
  }

  // if title isset
  if(isset($_POST['title'])) {
    // if title is different from old one
    if ($_POST['title'] != $current->{CATEGORY_TITLE}) {

      $post_title_var = upload_update_title("album_file", $pdo, $id, $_POST['title']);

      // if the database is updated add success message, if post var is empty add error message
      if($post_title_var == "success"){ $header_success_msgs[] = "Successfully updated album title!"; }
      else if($post_title_var != false){ $header_error_msgs[] = $post_title_var; }
    }
  }

  // if desc isset
  if(isset($_POST['desc'])) {
    // if desc is different from old one
    if ($_POST['desc'] != $current->{CATEGORY_DESCRIPTION}) {

      $post_desc_var = upload_update_desc("album_file", $pdo, $id, $_POST['desc']);

      // if the database is updated add success message, if post var is empty add error message
      if($post_desc_var == "success"){ $header_success_msgs[] = "Successfully updated album description!"; }
      else if($post_desc_var != false){ $header_error_msgs[] = $post_desc_var; }
    }
  }
}

$title = "Edit Album";
include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";

// grabbing the content as p
$stmt = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE " . CATEGORY_ID . " = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$p = $stmt->fetch();
?>

  <div class="content">

    <?php include "includes/header_msg_display.php"; ?>


        <h1 class="page-header page-header-resposive"><i class="fas fa-edit"></i> Edit Album</h1>

      <table class="form_table">


      <tr>
        <td>
          <a class="btn btn-danger" href="backend_albums_view.php">Back to Albums List</a>
        </td>
        <td>
          <a class="btn btn-green" href="backend_album_details.php?album=<?php echo $p->{CATEGORY_ID};?>">See posts within this album</a>
        </td>
      </tr>

      <tr>
          <td> <hr> </td><td> <hr> </td>
      </tr>


      <form method="post" enctype="multipart/form-data">
        <tr class="form-group">
          <td><h3 class="sub-header"><i class="fas fa-font"></i> Album Title</h3>
            <p>Enter a title for this album.</p>
            <input required minlength="3" maxlength="75" class="form-control" type="text" name="title" value="<?php echo $p->{CATEGORY_TITLE}?>"/></td>
        </tr>


        <tr class="form-group">
          <td><h3 class="sub-header"><i class="fas fa-pen"></i> Album Description</h3>
            <p>Enter a description of the album here (max 30,000 characters).</p>
            <textarea required minlength="10" maxlength="30000" style="min-height: 200px" id="mytextarea" class="form-control" name="desc"><?php echo $p->{CATEGORY_DESCRIPTION}?></textarea></td>
        </tr>


        <tr class="form-group">
          <td>
            <h3 class="sub-header"><i class="fas fa-file-image"></i> Replace Album Cover (jpg, png)</h3>
            <p class="sub-header">Image Preview:</p>
            <div class="editpost_img_preview" style="background-image: url('img/uploads/album_covers/<?php echo($p->{CATEGORY_FILENAME});?>');"></div>
              <br><br>
              <input id="album_file" class="inputfile" type="file" name="album_file"> <br> <label for="album_file">Choose a file</label>


            </td>
          <td></td>
        </tr>

        <tr>
          <td>
            <br>
          </td>
        </tr>


        <tr class="form-group">
          <td><button class="btn" name="submit" type="submit">Update</button></td>
        </tr>


      </form>

    </table>


    </div>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script>
  var editor_config = {
    forced_root_block : false,
    path_absolute : "/",
    selector: "textarea",
    plugins: [
      "advlist autolink lists link charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
    relative_urls: false,
    
  };
  tinymce.init(editor_config);
</script>

  </body>
  </html>
