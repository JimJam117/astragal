<?php

include_once ("includes/config.php");
include_once ("includes/db.php");

function upload_post($id, $pdo_obj) {
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }

  $uploadName = "image";
  //if ($id == "post_file") { $uploadName = "image"; }
  //else{ $uploadName = "album"; }

  // take file name and replace all spaces with dashes and make lowercase
  $FileName = strtolower(str_replace(" ", "_", $uploadName));

  // grab the file
  if(isset($_FILES["$id"])) {
      $file = $_FILES["$id"];
  }
  else{
    header("location: backend_404.php?err_msg=Internal error, the file id given does not match any of the files uploaded!");
    exit();
  }

  $Title = $_POST["title"];
  $Desc = $_POST["desc"];
  $Category_ID = $_POST["Category_ID"];

  // grab file vars
  $Fname = $file["name"];
  $Ftype = $file["type"];
  $FtempName = $file["tmp_name"];
  $Ferror = $file["error"];
  $Fsize = $file["size"];

  // explode the name, find the extension
  $Fexploded = explode(".", $Fname);
  $Fext = strtolower(end($Fexploded));

  // allowed extensions
  $possibleExt = array("jpg", "jpeg", "png", "gif");

  $inputCheck = false;

  //// INPUT CHECKS
  if(empty($Fext)) { return "Error uploading $uploadName, no file extention was detected."; }

  else if (!in_array($Fext, $possibleExt)) { return "Error uploading $uploadName, wrong file type. Cannot upload a $Fext, please try a jpg, png or gif."; }

  else if ($Ferror > 0) { "Error uploading $uploadName, there was an error uploading that file: $Ferror"; }

  else if ($Fsize > 40000000) { return "Error uploading $uploadName, file size is too big! Max size is 4GB."; }

  else if(empty($Title)) { return "Error uploading $uploadName, no title was given."; }

  else if(empty($Desc)) { return "Error uploading $uploadName, no description was given."; }

  else{ $inputCheck = true; }



  if ($inputCheck === true) {
    $NewFileName = $FileName . "." . uniqid("", true) . "." . $Fext;

    if ($id == "album_file") {
      $dest = "img/uploads/album_covers/" . $NewFileName;

      $stmt = $pdo_obj->prepare("INSERT INTO " . CATEGORIES_TABLE . " (" . CATEGORY_TITLE . ", " . CATEGORY_DESCRIPTION . ", " . CATEGORY_FILENAME . ") VALUES (:Title, :Desc, :NewFileName)");
    }
    else if ($id == "post_file") {
      $dest = "img/uploads/" . $NewFileName;

      $stmt = $pdo_obj->prepare("INSERT INTO " . POSTS_TABLE . " (" . POST_TITLE . ", " . POST_DESCRIPTION . ", " . POST_FILENAME . ", " . POST_CATEGORY_ID . ") VALUES (:Title, :Desc, :NewFileName, :Category_ID)");
    }
    // if is neither a "post_file" or a "album_file"
    else{
      header("location: backend_404.php?err_msg=Internal error, the file id given does not match any of the categories that can be uploaded to.");
      exit();
    }
    $stmt->bindParam(':Title', $Title, PDO::PARAM_STR);
    $stmt->bindParam(':Desc', $Desc, PDO::PARAM_STR);
    $stmt->bindParam(':Category_ID', $Category_ID, PDO::PARAM_STR);
    $stmt->bindParam(':NewFileName', $NewFileName, PDO::PARAM_STR);
    $stmt->execute();

    move_uploaded_file($FtempName, $dest);
    return "success";
  }

}





















































































































<?php

include_once ("includes/config.php");
include_once ("includes/db.php");


if (isset($_POST["submit"])) {
    //echo("POST");
    $FileName = "image";  //$_POST["filename"];
    if (empty($FileName)) {
        $FileName = "Image";
    }
    else{
        $FileName = strtolower(str_replace(" ", "_", $FileName));
    }
    $Title = $_POST["title"];
    $Desc = $_POST["desc"];
    $Album_ID = $_POST["album_id"];

    $file = $_FILES['file'];

    $Fname = $file["name"];
    $Ftype = $file["type"];
    $FtempName = $file["tmp_name"];
    $Ferror = $file["error"];
    $Fsize = $file["size"];

    $Fexploded = explode(".", $Fname);
    $Fext = strtolower(end($Fexploded));

    $possibleExt = array("jpg", "jpeg", "png", "gif");

    $inputCheck = false;
    $_err_emptyExtenion = false;
    $_err_wrongFileType = false;
    $_err_generalError = false;
    $_err_sizeTooBig = false;
    $_err_NoTitle = false;
    $_err_NoDescription = false;
    //// INPUT CHECKS
    if(empty($Fext)) {
        $_err_emptyExtenion = true;
    }
    else if(!in_array($Fext, $possibleExt)) {
      $_err_wrongFileType = true;
    }
    else if($Ferror > 0) {
      $_err_generalError = true;
    }
    else if($Fsize > 40000000) {
        $_err_sizeTooBig = true;
    }
    else if(empty($Title)) {
      $_err_NoTitle = true;
    }
    else if(empty($Desc)) {
      $_err_NoDescription = true;
    }
    else{
      $inputCheck = true;
    }



    //// INPUT CHECKS END

    if ($inputCheck === true) {
    $NewFileName = $FileName . "." . uniqid("", true) . "." . $Fext;
    $dest = "img/uploads/" . $NewFileName;

    //debug stuff
    //echo("Filename: " . $NewFileName);
    //echo("Destination: " . $dest);

    $query = "SELECT * FROM gal";
    $result = $db->query($query);
    $rowsNum = mysqli_num_rows($result);



    $insertQuery = "INSERT INTO gal (Gal_Title, Gal_Desc, Gal_FileName, Gal_This_Post_Album_ID) VALUES ('$Title', '$Desc', '$NewFileName', $Album_ID)";
    $db->query($insertQuery);

    move_uploaded_file($FtempName, $dest);
    header("location: backend_new_post.php?post=$id&success_msg=Image posted!");
    }
    else {

      if($_err_emptyExtenion == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, no file uploaded for the image. Give your post an image, thats like, the whole point of this app lmao");
      }
      else if($_err_wrongFileType == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, wrong file type bozo. Cannot upload a $Fext, please try a jpg, png or gif.");
      }
      else if($_err_generalError == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, there was an error uploading that file: $Ferror");
      }
      else if($_err_sizeTooBig == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, file size is too big! Max size is 4GB.");
      }
      else if($_err_NoTitle == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, no title was given!");
      }
      else if($_err_NoDescription == true) {
        header("location: backend_new_post.php?post=$id&err_msg=Error, no description was given!");
      }
      else {
        header("location: backend_new_post.php?post=$id&err_msg=Something strange happened. Input checks are false but no errors have fired. Spooky.");
      }
    }

}




// checks for input errors
