<?php

include_once ("includes/config.php");
include_once ("includes/db.php");

function upload_backend_index($id, $pdo_obj) {
  if (empty($id)) {
    return "Error with the id '$id', please make sure you entered this into the function correctly.";
  }

  if ($id == "pp_file") { $uploadName = "profile picture"; }
  else{ $uploadName = "background picture"; }

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

  // drab file vars
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
  if(empty($Fext)) { return null; }

  else if (!in_array($Fext, $possibleExt)) { return "Error uploading $uploadName, wrong file type. Cannot upload a $Fext, please try a jpg, png or gif."; }

  else if ($Ferror > 0) { "Error uploading $uploadName, there was an error uploading that file: $Ferror"; }

  else if ($Fsize > 40000000) { return "Error uploading $uploadName, file size is too big! Max size is 4GB."; }

  else{ $inputCheck = true; }



  if ($inputCheck === true) {
    $NewFileName = $FileName . "." . uniqid("", true) . "." . $Fext;
    $dest = "img/uploads/pref/" . $NewFileName;

    if ($id == "pp_file") {
      $stmt = $pdo_obj->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_PROFILE_IMAGE_LOCATION . " = :NewFileName WHERE " . SETTINGS_PROFILE_ID . " = 1");
    }
    else {
      $stmt = $pdo_obj->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_BK_IMAGE_LOCATION . " = :NewFileName WHERE " . SETTINGS_PROFILE_ID . " = 1");
    }
    $stmt->bindParam(':NewFileName', $NewFileName, PDO::PARAM_STR);
    $stmt->execute();

    move_uploaded_file($FtempName, $dest);
    return "success";
  }

}
