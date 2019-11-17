<?php

include_once "includes/config.php";
include_once "includes/db.php";

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

// get the album id
if(isset($_GET['album'])) {

  $thisAlbum = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . ' WHERE ' . CATEGORY_ID . ' = :id');
  $thisAlbum->bindParam(':id', $_GET['album'], PDO::PARAM_INT);
  $thisAlbum->execute();
  $thisAlbum = $thisAlbum->fetch();

  if(!$thisAlbum) { header("location: backend_404.php?err_msg=No ablum for the given id!"); exit(); }
  $title = "Album '" . $thisAlbum->{CATEGORY_TITLE} ."' Details" ;
}
else{ // This fires if there IS NO ROW
  header("location: backend_404.php?err_msg=No ablum id given!"); exit();
}


# PAGINATION
// setting results per page //
$results_per_page = 6;

// finding num of pages //
$num_of_rows = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_CATEGORY_ID . " = :id");
$num_of_rows->bindParam(':id', $_GET['album'], PDO::PARAM_INT);
$num_of_rows->execute();

$num_of_rows = $num_of_rows->rowCount();
$number_of_pages = ceil($num_of_rows / $results_per_page);

if(!isset($_GET['page'])){
  $thisPage = 1;
}
else {
  if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
    $thisPage = 1;
    header("location: backend_albums_details.php?album=" . $_GET['album']);
  }
  $thisPage = $_GET['page'];
}

$this_page_first_result = ($thisPage-1)*$results_per_page;



//Delete post code
if(isset($_GET['action']) && isset($_GET['id'])){

  $actionPost = $pdo->prepare('SELECT * FROM ' . POSTS_TABLE . ' WHERE ' . POST_ID . ' = :id');
  $actionPost->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $actionPost->execute();
  $actionPost = $actionPost->fetch();

  // if the action is set to delete AND actionpost is not null
  if($_GET['action'] === "delete" && $actionPost){
    $delPostName = $actionPost->{POST_TITLE};

    // check if featured image
    $fi_query = $pdo->prepare("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
    $fi_query->execute();
    $fi_query = $fi_query->fetch();

    // if is a featured image, update the settings table to remove the associated id
    if ($fi_query->{SETTINGS_FEATURED_IMAGE_ID_1} == $_GET['id']) {
      $fi_remove_query = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_FEATURED_IMAGE_ID_1 . " = '0' WHERE " . SETTINGS_PROFILE_ID . " = 1");
      $fi_remove_query->execute();
    }
    if ($fi_query->{SETTINGS_FEATURED_IMAGE_ID_2} == $_GET['id']) {
      $fi_remove_query = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_FEATURED_IMAGE_ID_2 . " = '0' WHERE " . SETTINGS_PROFILE_ID . " = 1");
      $fi_remove_query->execute();
    }
    if ($fi_query->{SETTINGS_FEATURED_IMAGE_ID_3} == $_GET['id']) {
      $fi_remove_query = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_FEATURED_IMAGE_ID_3 . " = '0' WHERE " . SETTINGS_PROFILE_ID . " = 1");
      $fi_remove_query->execute();
    }

    // delete post in post table
    $stnt = $pdo->prepare('DELETE FROM ' . POSTS_TABLE . ' WHERE ' . POST_ID . " = :id");
    $stnt->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
    $stnt->execute();
    $header_success_msgs[] = "Post: '$delPostName' deleted (forevvverr).";
  }
}

include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
?>

<div class="content">

  <?php include "includes/header_msg_display.php"; ?>

  <div class="album_details_top">

    <br>
    <table class="form_table">
      <tr>
        <td> <h1><i class="fas fa-folder-open"></i> Album "<?php echo $thisAlbum->{CATEGORY_TITLE}; ?>" Details</h1> </td>
      </tr>
      <tr>
        <td><h3><i class="fas fa-file-image"></i>Album Cover:</h3>
          <div class="alb_details_pic" style="background-image: url('img/uploads/album_covers/<?php echo $thisAlbum->{CATEGORY_FILENAME}; ?>'" alt="<?php echo $thisAlbum->{CATEGORY_TITLE}; ?>"></div></td>
      </tr>
      <tr>
        <td><h3><i class="fas fa-pen"></i>Album Description:</h3>
          <p> <?php echo $thisAlbum->{CATEGORY_DESCRIPTION}; ?> </p></td>
      </tr>

      <tr>
        <td></td>
      </tr>

      <tr>
        <td class="alb_details_td_padding">
          <a class="btn" href="album_single.php?post=<?php echo $thisAlbum->{CATEGORY_ID};?>" >View Album</a>
          <a class="btn btn-warning" href="backend_edit_album.php?post=<?php echo $thisAlbum->{CATEGORY_ID}; ?>">Edit Album</a>
        </td>
      </tr>
    </table>
  </div>
  <br>
<hr>
<br>

  <?php
  // Counting the posts in the album
  $posts = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_CATEGORY_ID . " = :id ORDER BY " . POST_ID . " DESC");
  $posts->bindParam(':id', $_GET['album'], PDO::PARAM_INT);
  $posts->execute();

  if ($posts->rowCount() == 0) { //IF THERE ARE NO POSTS IN THE ALBUM ?>
    <h1 class="page-header"><i class="far fa-images"></i> Posts in Album</h1>
    <h4>No posts in this ablum yet! <a href="backend_new_post.php">Create a post</a> or assign an existing post to this album</h4>

    <?php
  }
  else { // IF THERE ARE POSTS IN THE ALBUM
    ?>
    <h1 class="page-header"><i class="far fa-images"></i> Posts in Album</h1>
    <div class="table">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th class="thumbnailColumn">Thumbnail</th>
            <th class="wordCountColumn">Description Word Count</th>
            <th class="actionsColumn">Actions</th>

          </tr>
        </thead>
        <tbody>

          <?php foreach($posts as $row){?>
            <tr>
              <td><?php echo $row->{POST_TITLE};?></td>
              <td class="thumbnailColumn" style="background-image: url('img/uploads/<?php echo $row->{POST_FILENAME};?>'); background-size: contain; background-repeat: no-repeat;" ></td>
              <td class="wordCountColumn"><?php echo str_word_count($row->{POST_DESCRIPTION});?> words</td>


              <td><a href="single.php?post=<?php echo $row->{POST_ID};?>" class="btn btnBlock">View</a>
                <a href="backend_edit_post.php?post=<?php echo $row->{POST_ID};?>" class="btn btn-warning btnBlock">Edit</a>


                <a class="btn btn-danger btnBlock"
                onClick="javascript: return confirm('Please confirm deletion');"
                href="backend_album_details.php?album=<?php echo $_GET['album']; ?>&action=delete&id=<?php echo $row->{POST_ID};?>">
                Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php }



  // pagination
  echo "<div class='backend_pagination_container'>
                  <div class='backend_pagination_btn_container'>";

  if ($thisPage > 1) {
    $lastPage = $thisPage-1;
    echo "<a class='backend_pagination_btn' href='backend_album_details.php?album=" . $_GET['album'] . "&page=$lastPage'><<</a>";
  }
  if ($number_of_pages > $thisPage) {
    $nextPage = $thisPage+1;
    echo "<a class='backend_pagination_btn' href='backend_album_details.php?album=" . $_GET['album'] . "&page=$nextPage'>>></a>";
  }

  echo "</div><br>";

  for ($page=1; $page <= $number_of_pages; $page++) {
    if ($page == $thisPage) {
      echo "<b class='backend_pagination current_page' href='backend_album_details.php?album=" . $_GET['album'] . "&page=$page'>$page</b>";
    }
    else {
      echo "<a class='backend_pagination' href='backend_album_details.php?album=" . $_GET['album'] . "&page=$page'>$page</a>";
    }
  }


  echo "</div>";
   ?>

  <br><hr><br>
  <a class="btn btn-danger" href="backend_albums_view.php">Back to Albums List</a>
</div>
</body>
</html>
