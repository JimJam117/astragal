<?php

include_once "includes/config.php";
include_once "includes/db.php";

$title = "Posts List";

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

# PAGINATION
// setting results per page //
$results_per_page = 6;

// finding num of pages //
$num_of_rows = $pdo->query("SELECT * FROM " . POSTS_TABLE);
$num_of_rows = $num_of_rows->rowCount();
$number_of_pages = ceil($num_of_rows / $results_per_page);

if(!isset($_GET['page'])){
  $thisPage = 1;
}
else {
  if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
    $thisPage = 1;
    header("location: backend_posts_view.php?");
  }
  $thisPage = $_GET['page'];
}

$this_page_first_result = ($thisPage-1)*$results_per_page;



if(isset($_GET['action']) && isset($_GET['id'])){

  $actionPost = $pdo->prepare('SELECT * FROM ' . POSTS_TABLE . ' WHERE ' . POST_ID . ' = :id');
  $actionPost->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $actionPost->execute();
  $actionPost = $actionPost->fetch();

  // check if featured image
  //$fi_query = $pdo->prepare("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
  //$fi_query->execute();
  //$fi_query = $fi_query->fetch();

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

  <h1 class="page-header"><i class="far fa-images"></i> All Posts</h1>
  <div class="table">

    <?php
    $stnt = $pdo->prepare('SELECT * FROM ' . POSTS_TABLE . ' ORDER BY ' . POST_ID . ' DESC LIMIT :this_page_first_result, :results_per_page');
    $stnt->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
    $stnt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
    $stnt->execute();

    // if there are no posts
    if($stnt->rowCount() <= 0) {
      echo "<h2 class='noPostsFoundMessage'>No posts found 😢</h2>";
    }

    // if there are posts
    else { ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th class="thumbnailHideColumn">Thumbnail</th>
            <th class="wordCountColumn">Description Word Count</th>
            <th class="albumColumn">Album</th>
            <th class="actionsColumn">Actions</th>

          </tr>
        </thead>
        <tbody>

          <?php foreach($stnt as $row){ ?>
            <tr>
              <td> <h4> <?php echo $row->{POST_TITLE};?></h4></td>
              <td class="thumbnailHideColumn" style="background-image: url('img/uploads/<?php echo $row->{POST_FILENAME};?>'); background-size: contain; background-repeat: no-repeat;" ></td>
              <td class="wordCountColumn"><?php echo str_word_count($row->{POST_DESCRIPTION});?> words</td>

              <td class="albumColumn">

                <?php // display album name
                $assocAlbumID = 0;
                if ($row->{POST_CATEGORY_ID} == 0) {
                  echo "No Album";
                }
                else{
                  $category_stnt = $pdo->prepare('SELECT * FROM ' . CATEGORIES_TABLE . ' ORDER BY ' . CATEGORY_ID . ' DESC');
                  $category_stnt->execute();
                  foreach($category_stnt as $catRow) {
                    if ($catRow->{CATEGORY_ID} == $row->{POST_CATEGORY_ID}) {
                      $assocAlbumID = $catRow->{CATEGORY_ID};
                      echo $catRow->{CATEGORY_TITLE};
                    }
                  }
                }
                ?>
              </td>

              <td>
                <a href="single.php?post=<?php echo $row->{POST_ID};?>" class="btn btnBlock">View</a>
                <a href="backend_edit_post.php?post=<?php echo $row->{POST_ID};?>" class="btn btn-warning btnBlock">Edit</a>
                <a class="btn btn-danger btnBlock" onClick="javascript: return confirm('Please confirm deletion of this post');" href="backend_posts_view.php?action=delete&id=<?php echo $row->{POST_ID};?>">Delete</a>
                <?php if (!$assocAlbumID == 0) { ?>
                  <a href="backend_album_details.php?album=<?php echo($assocAlbumID);?>" class="btn btn-green btnBlock">Album</a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <br>

  <?php
  // pagination
  echo "<div class='backend_pagination_container'>
  <div class='backend_pagination_btn_container'>";

  if ($thisPage > 1) {
    $lastPage = $thisPage-1;
    echo "<a class='backend_pagination_btn' href='backend_posts_view.php?page=$lastPage'><<</a>";
  }
  if ($number_of_pages > $thisPage) {
    $nextPage = $thisPage+1;
    echo "<a class='backend_pagination_btn' href='backend_posts_view.php?page=$nextPage'>>></a>";
  }

  echo "</div><br>";

  for ($page=1; $page <= $number_of_pages; $page++) {
    if ($page == $thisPage) {
      echo "<b class='backend_pagination current_page' href='backend_posts_view.php?page=$page'>$page</b>";
    }
    else {
      echo "<a class='backend_pagination' href='backend_posts_view.php?page=$page'>$page</a>";
    }
  }


  echo "</div>";
  ?>


  <br><br>
  <a class="btn" href="backend_new_post.php">Create Post</a>
</div>


</body>
</html>
