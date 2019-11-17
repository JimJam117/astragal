<?php

include_once ("includes/config.php");
include_once ("includes/db.php");

//
if (isset($_POST["submit"]) && isset($_FILES['file'])) {
    if($isUpdatingImage == true){
    $FileName = "image";  //$_POST["filename"];
    if (empty($FileName)) { $FileName = "Image"; }
    else{ $FileName = strtolower(str_replace(" ", "_", $FileName)); }

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

    //// INPUT CHECKS
    if(empty($Fext)) {
      $_err_emptyExtenion = true;
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, no file uploaded for the image. Give your post an image, thats like, the whole point of this app lmao");
    }
    else{
    if(!in_array($Fext, $possibleExt)) {
      $_err_wrongFileType = true;
      //header("location: backend_edit_post.php?post=$id&err_msg=Error, wrong file type bozo. Cannot upload a $Fext, please try a jpg, png or gif.");
    }
    else{
        if($Ferror > 0){
          $_err_generalError = true;
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, there was an error uploading that file: $Ferror");
        }
        else{
          if($Fsize > 40000000) {
            $_err_sizeTooBig = true;
            //header("location: backend_edit_post.php?post=$id&err_msg=Error, file size is too big! Max size is 4GB.");
          }
          else {
                $inputCheck = true;
              }
            }
          }
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


    if(empty($_updateID)) {
      echo "ERROR, the _updateID is empty. Something has gone wrong here";
      exit();
    }
    $insertQuery = "UPDATE gal SET Gal_FileName = '$NewFileName' WHERE Gal_ID = '$_updateID'";
    $db->query($insertQuery);

    move_uploaded_file($FtempName, $dest);
    $header_msgs[] = "success_msg=Image successfully updated!";
    //header("location: backend_edit_post.php?post=$id&success_msg=Image successfully updated!");
    }
    else {

      if($_err_emptyExtenion == true) {
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, no file uploaded for the image. Give your post an image, thats like, the whole point of this app lmao");
      }
      else if($_err_wrongFileType == true) {
        $header_msgs[] = "err_msg=Error, wrong file type bozo. Cannot upload a $Fext, please try a jpg, png or gif.";
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, wrong file type bozo. Cannot upload a $Fext, please try a jpg, png or gif.");
      }
      else if($_err_generalError == true) {
        $header_msgs[] = "err_msg=Error, there was an error uploading that file: $Ferror";
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, there was an error uploading that file: $Ferror");
      }
      else if($_err_sizeTooBig == true) {
        $header_msgs[] = "err_msg=Error, file size is too big! Max size is 4GB.";
        //header("location: backend_edit_post.php?post=$id&err_msg=Error, file size is too big! Max size is 4GB.");
      }
      else {
        $header_msgs[] = "err_msg=Something strange happened. Input checks are false but no errors have fired. Spooky.";
        //header("location: backend_edit_post.php?post=$id&err_msg=Something strange happened. Input checks are false but no errors have fired. Spooky.");
      }
    }

}
}


// checks for input errors
