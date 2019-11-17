<?php

include_once ("includes/config.php");
include_once ("includes/db.php");


if(isset($_GET['entity']) && isset($_GET['action']) && isset($_GET['id'])){

    $entity = mysqli_real_escape_string($db, $_GET['entity']);
    $action = mysqli_real_escape_string($db, $_GET['action']);
    $id = mysqli_real_escape_string($db, $_GET['id']);

    if($action === "delete"){
        $query = "DELETE FROM albums WHERE Alb_ID = '$id'";

    }else{

    }
    $db->query($query);
}







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
    $dest = "img/uploads/album_covers/" . $NewFileName;

    //debug stuff
    //echo("Filename: " . $NewFileName);
    //echo("Destination: " . $dest);
  //  echo("Alb Title: " . $Title);
  //  echo("Alb Desc: " . $Desc);

    $query = "SELECT * FROM albums";
    $result = $db->query($query);
    $rowsNum = mysqli_num_rows($result);



    $insertQuery = "INSERT INTO albums (Alb_Title, Alb_Desc, Alb_This_Album_Category_ID, Alb_Cover_FileName) VALUES ('$Title', '$Desc', 0, '$NewFileName')";
    $db->query($insertQuery);

    move_uploaded_file($FtempName, $dest);
    header("location: backend_new_album.php?post=$id&success_msg=Album successfully created!");
    }
    else {

      if($_err_emptyExtenion == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, no file uploaded for the cover image. Give the album a cover you meanie!");
      }
      else if($_err_wrongFileType == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, wrong file type bozo. Cannot upload a $Fext, please try a jpg, png or gif.");
      }
      else if($_err_generalError == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, there was an error uploading that file: $Ferror");
      }
      else if($_err_sizeTooBig == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, file size is too big! Max size is 4GB.");
      }
      else if($_err_NoTitle == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, no title was given!");
      }
      else if($_err_NoDescription == true) {
        header("location: backend_new_album.php?post=$id&err_msg=Error, no description was given!");
      }
      else {
        header("location: backend_new_album.php?post=$id&err_msg=Something strange happened. Input checks are false but no errors have fired. Spooky.");
      }
    }

}
