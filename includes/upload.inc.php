<?php

include_once ("includes/config.php");
include_once ("includes/db.php");

function upload($id, $pdo_obj, $Title, $Desc, $Category_ID) {

  // this is used to determine if the upload is a post or a category
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }
  if ($id == "post_file") { $uploadName = "image"; }
  else{ $uploadName = "album"; }

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
    $stmt->bindParam(':NewFileName', $NewFileName, PDO::PARAM_STR);

    // this is not needed for uploading ablums, so it will be set to null when doing so. No need to bind it if it's not going to be used
    if ($Category_ID != null) {
      $stmt->bindParam(':Category_ID', $Category_ID, PDO::PARAM_STR);
    }

    $stmtBool = $stmt->execute();
    if ($stmtBool == false){
      return "Error uploading to the database, make sure that your database is set up correctly. This error may occur if you have too many columns within your POSTS/ALBUMS table, please take a look at the config.php file.";
    }

    move_uploaded_file($FtempName, $dest);
    return "success";
  }

}






// -- edit function

function upload_update_img($id, $pdo_obj, $upload_id) {

  // this is used to determine if the upload is a post or a category
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }
  if ($id == "post_file") { $uploadName = "image"; }
  else{ $uploadName = "album"; }

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

  else{ $inputCheck = true; }



  if ($inputCheck === true) {
    $NewFileName = $FileName . "." . uniqid("", true) . "." . $Fext;

    if ($id == "album_file") {
      $dest = "img/uploads/album_covers/" . $NewFileName;

      $stmt = $pdo_obj->prepare("UPDATE " . CATEGORIES_TABLE . " SET " . CATEGORY_FILENAME . " = :NewFileName WHERE " . CATEGORY_ID . " = :upload_id");
    }
    else if ($id == "post_file") {
      $dest = "img/uploads/" . $NewFileName;

      $stmt = $pdo_obj->prepare("UPDATE " . POSTS_TABLE . " SET " . POST_FILENAME . " = :NewFileName WHERE " . POST_ID . " = :upload_id");
    }
    // if is neither a "post_file" or a "album_file"
    else{
      header("location: backend_404.php?err_msg=Internal error, the file id given does not match any of the categories that can be uploaded to.");
      exit();
    }
      $stmt->bindParam(':upload_id', $upload_id, PDO::PARAM_STR);
    $stmt->bindParam(':NewFileName', $NewFileName, PDO::PARAM_STR);

    $stmtBool = $stmt->execute();
    if ($stmtBool == false){
      return "Error uploading to the database, make sure that your database is set up correctly. This error may occur if you have too many columns within your POSTS/ALBUMS table, please take a look at the config.php file.";
    }

    move_uploaded_file($FtempName, $dest);
    return "success";
  }
}

function upload_update_title($id, $pdo_obj, $upload_id, $Title) {

  // this is used to determine if the upload is a post or a category
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }
  if ($id == "post_file") { $uploadName = "image"; }
  else{ $uploadName = "album"; }


  if (empty($Title)) {return "Error uploading $uploadName, no title was given.";}

  else { $inputCheck = true; }



  if ($inputCheck === true) {
    if ($id == "album_file") {
      $stmt = $pdo_obj->prepare("UPDATE " . CATEGORIES_TABLE . " SET " . CATEGORY_TITLE . " = :NewTitle WHERE " . CATEGORY_ID . " = :upload_id");
    }
    else if ($id == "post_file") {
      $stmt = $pdo_obj->prepare("UPDATE " . POSTS_TABLE . " SET " . POST_TITLE . " = :NewTitle WHERE " . POST_ID . " = :upload_id");
    }
    // if is neither a "post_file" or a "album_file"
    else{
      header("location: backend_404.php?err_msg=Internal error, the file id given does not match any of the categories that can be uploaded to.");
      exit();
    }
    $stmt->bindParam(':upload_id', $upload_id, PDO::PARAM_STR);
    $stmt->bindParam(':NewTitle', $Title, PDO::PARAM_STR);

    $stmtBool = $stmt->execute();
    if ($stmtBool == false){
      return "Error sending the request to update the title to the database, make sure that your database is set up correctly. This error may occur if you have too many columns within your POSTS/ALBUMS table, please take a look at the config.php file.";
    }

    return "success";
  }
}

function upload_update_desc($id, $pdo_obj, $upload_id, $Desc) {

  // this is used to determine if the upload is a post or a category
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }
  if ($id == "post_file") { $uploadName = "image"; }
  else{ $uploadName = "album"; }


  if (empty($Desc)) {return "Error uploading $uploadName, no description was given.";}

  else{ $inputCheck = true; }



  if ($inputCheck === true) {

    if ($id == "album_file") {
      $stmt = $pdo_obj->prepare("UPDATE " . CATEGORIES_TABLE . " SET " . CATEGORY_DESCRIPTION . " = :NewDesc WHERE " . CATEGORY_ID . " = :upload_id");
    }
    else if ($id == "post_file") {
      $stmt = $pdo_obj->prepare("UPDATE " . POSTS_TABLE . " SET " . POST_DESCRIPTION . " = :NewDesc WHERE " . POST_ID . " = :upload_id");
    }
    // if is neither a "post_file" or a "album_file"
    else{
      header("location: backend_404.php?err_msg=Internal error, the file id given does not match any of the categories that can be uploaded to.");
      exit();
    }
    $stmt->bindParam(':upload_id', $upload_id, PDO::PARAM_STR);
    $stmt->bindParam(':NewDesc', $Desc, PDO::PARAM_STR);

    $stmtBool = $stmt->execute();
    if ($stmtBool == false){
      return "Error sending the request to update the description to the database, make sure that your database is set up correctly. This error may occur if you have too many columns within your POSTS/ALBUMS table, please take a look at the config.php file.";
    }

    return "success";
  }
}


function upload_update_catId($id, $pdo_obj, $upload_id, $Category_ID) {

  // this is used to determine if the upload is a post or a category
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }
  $uploadName = "image";

  // input check is always true with category id, because it can be set to none
  $inputCheck = true;


  if ($inputCheck === true) {
    $stmt = $pdo_obj->prepare("UPDATE " . POSTS_TABLE . " SET " . POST_CATEGORY_ID . " = :NewCatID WHERE " . POST_ID . " = :upload_id");

    $stmt->bindParam(':upload_id', $upload_id, PDO::PARAM_STR);
    $stmt->bindParam(':NewCatID', $Category_ID, PDO::PARAM_STR);

    $stmtBool = $stmt->execute();
    if ($stmtBool == false){
      return "Error sending the request to update the category_id to the database, make sure that your database is set up correctly. This error may occur if you have too many columns within your POSTS/ALBUMS table, please take a look at the config.php file.";
    }

    return "success";
  }
}
