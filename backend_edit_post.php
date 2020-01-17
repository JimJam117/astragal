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
  header("location: backend_404.php?err_msg=Error, the id in the url given does not match any of the posts!");
  exit();
}

// grabbing current to compare if there are any changes
$stmt = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_ID . " = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$current = $stmt->fetch();


if(isset($_POST['submit'])){
  if(isset($_FILES['post_file']) && $_FILES['post_file']['size'] != 0) {
    $post_var = upload_update_img("post_file", $pdo, $id);

    // if the database is updated add success message, if post var is empty add error message
    if($post_var == "success"){ $header_success_msgs[] = "Successfully updated image!"; }
    else if($post_var != false){ $header_error_msgs[] = $post_var; }
  }

  // if title isset
  if(isset($_POST['title'])) {
    // if title is different from old one
    if ($_POST['title'] != $current->{POST_TITLE}) {

      $post_title_var = upload_update_title("post_file", $pdo, $id, $_POST['title']);

      // if the database is updated add success message, if post var is empty add error message
      if($post_title_var == "success"){ $header_success_msgs[] = "Successfully updated post title!"; }
      else if($post_title_var != false){ $header_error_msgs[] = $post_title_var; }
    }
  }

  // if desc isset
  if(isset($_POST['desc'])) {
    // if desc is different from old one
    if ($_POST['desc'] != $current->{POST_DESCRIPTION}) {

      $post_desc_var = upload_update_desc("post_file", $pdo, $id, $_POST['desc']);

      // if the database is updated add success message, if post var is empty add error message
      if($post_desc_var == "success"){ $header_success_msgs[] = "Successfully updated post description!"; }
      else if($post_desc_var != false){ $header_error_msgs[] = $post_desc_var; }
    }
  }

  // if catID isset
  if(isset($_POST['Category_ID'])) {
    // if catID is different from old one
    if ($_POST['Category_ID'] != $current->{POST_CATEGORY_ID}) {

      $post_catid_var = upload_update_catId("post_file", $pdo, $id, $_POST['Category_ID']);

      // if the database is updated add success message, if post var is empty add error message
      if($post_catid_var == "success"){ $header_success_msgs[] = "Successfully updated post album!"; }
      else if($post_catid_var != false){ $header_error_msgs[] = $post_catid_var; }
    }
  }
}

$title = "Create Post";
include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";

// grabbing the content as p
$stmt = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_ID . " = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$p = $stmt->fetch();
?>
<div class="content">

<?php include "includes/header_msg_display.php"; ?>

  <h1 class="page-header page-header-resposive"><i class="fas fa-edit"></i> Edit Post</h1>

  <table class="form_table">

    <form method="post" enctype="multipart/form-data">
      <tr class="form-group">
        <td><h3 class="sub-header"><i class="fas fa-font"></i> Post Title</h3>
          <p>Enter a title for this post.</p>
          <input required minlength="3" maxlength="75" class="form-control" type="text" name="title" value="<?php echo $p->{POST_TITLE}?>"/></td>
        </tr>



        <tr class="form-group">
          <td><h3 class="sub-header"><i class="fas fa-folder-plus"></i> Post Album</h3>
            <p>If you want to add this post to a pre-existing album, choose the album here. You can always come back and change the album later.</p>
            <select name="Category_ID">
              <option value=0>None</option>
              <?php
              $assocCatID = 0;

              $cats = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " ORDER BY " . CATEGORY_ID . " DESC");
              $cats->execute();

              foreach ($cats as $row){
                $val = $row->{CATEGORY_ID};
                $name = $row->{CATEGORY_TITLE};
                if ($val == $p->{POST_CATEGORY_ID}) {
                  $assocCatID = $val;
                  echo "<option value='$val' selected>$name</option>";
                }else{
                  echo "<option value='$val'>$name</option>";
                }
              }
              ?>
            </select></td>
          </tr>

          <tr class="form-group">
            <td><h3 class="sub-header"><i class="fas fa-pen"></i> Post Description</h3>
              <p>Enter a description of the post here (max 30,000 characters).</p>
              <textarea required minlength="10" maxlength="30000" style="min-height: 200px" id="mytextarea" class="form-control" name="desc"><?php echo $p->{POST_DESCRIPTION}?></textarea></td>
            </tr>


            <tr class="form-group">
              <td>
                <h3 class="sub-header"><i class="fas fa-file-image"></i> Replace Image (jpg, png)</h3>
                <p class="sub-header">Current Image:</p>
                <div class="editpost_img_preview" style="background-image: url('img/uploads/<?php echo($p->{POST_FILENAME});?>');"></div>
                <br> <br>
                <input id="post_file" class="inputfile" type="file" name="post_file"> <br>  <label for="post_file">Choose a file</label>
              </td>
              <td>

              </td>
            </tr>

            <tr>
              <td>
                <br>
              </td>
            </tr>

            <tr class="form-group">
              <td><button class="btn" name="submit" type="submit">Update</button></td>
            </tr>


            <tr>
              <td><a href="backend_posts_view.php" class="btn btn-danger">Back to All Posts</a></td>
              <?php /* if($assocAlbumID != 0) { ?>
                <td><a href="backend_album_details.php?album=<?php echo($assocAlbumID);?>" class="btn btn-warning">Back to Album</a></td>
              <?php } */ ?>
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
