<?php

include_once "includes/config.php";
include_once "includes/db.php";

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

include_once "includes/upload.inc.php";

if(isset($_POST['submit'])){
  if(isset($_FILES['post_file'])) {
    $post_var = upload("post_file", $pdo, $_POST["title"], $_POST["desc"], $_POST["Category_ID"]);

    // if the database is updated add success message, if post var is empty add error message
    if($post_var == "success"){ $header_success_msgs[] = "Successfully added post!"; }
    else if($post_var != false){ $header_error_msgs[] = $post_var; }
  }

}

$title = "Create Post";
include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
?>

<div class="content">

  <?php include "includes/header_msg_display.php"; ?>

  <h1 class="page-header page-header-resposive"><i class="fas fa-plus-square"></i> Create Post</h1>
  <table class="form_table">
    <form method="post" enctype="multipart/form-data">

      <tr class="form-group">
        <td><h3 class="sub-header"><i class="fas fa-font"></i> Post Title</h3>
          <p>Enter a title for this post.</p>
          <input required minlength="3" maxlength="75" class="form-control" type="text" name="title"/></td>
        </tr>

        <br>

        <tr class="form-group">
          <td><h3 class="sub-header"><i class="fas fa-folder-plus"></i> Post Album</h3>
            <p>If you want to add this new post to a pre-existing album, choose the album here. You can always come back and change the album later.</p>
            <select name="Category_ID">
              <option value=0>None</option>
            <?php
                $cats = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE);
                $cats->execute();

                foreach ($cats as $row){
                  $val = $row->{CATEGORY_ID};
                  $name = $row->{CATEGORY_TITLE};
                  echo "<option value='$val'>$name</option>";

                }
                ?>
              </select></td>
            </tr>

            <tr class="form-group">
              <td><h3 class="sub-header"><i class="fas fa-pen"></i> Post Description</h3>
                <p>Enter a description of the post here (max 30,000 characters).</p>
                <textarea required minlength="10" maxlength="30000" style="min-height: 400px" id="mytextarea" class="form-control" name="desc"></textarea></td>
              </tr>

              <tr class="form-group">
                <td><h3 class="sub-header"><i class="fas fa-file-image"></i> Post Image (jpg, png)</h3>
                  <br>
                  <input id="post_file" class="inputfile" required type="file" name="post_file"> <br> <label for="post_file">Choose a file</label>
                </td>
              </tr>

              <tr><td><br></td></tr>

              <tr class="form-group">
                <td><button class="btn" name="submit" type="submit">Add Post</button></td>
              </tr>

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
