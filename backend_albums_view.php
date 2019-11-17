<?php

include_once "includes/config.php";
include_once "includes/db.php";

$title = "Albums List";

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

# PAGINATION
// setting results per page //
$results_per_page = 6;

// finding num of pages //
$num_of_rows = $pdo->query("SELECT * FROM " . CATEGORIES_TABLE);
$num_of_rows = $num_of_rows->rowCount();
$number_of_pages = ceil($num_of_rows / $results_per_page);

if(!isset($_GET['page'])){
  $thisPage = 1;
}
else {
  if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
    $thisPage = 1;
    header("location: backend_albums_view.php?");
  }
  $thisPage = $_GET['page'];
}

$this_page_first_result = ($thisPage-1)*$results_per_page;



if(isset($_GET['action']) && isset($_GET['id'])){

  $actionPost = $pdo->prepare('SELECT * FROM ' . CATEGORIES_TABLE . ' WHERE ' . CATEGORY_ID . ' = :id');
  $actionPost->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
  $actionPost->execute();
  $actionPost = $actionPost->fetch();

  // if the action is set to delete AND actionpost is not null
  if($_GET['action'] === "delete" && $actionPost){
    $delPostName = $actionPost->{CATEGORY_TITLE};


    // grabs posts with the albums id
    $postsToUnassign = $pdo->prepare('SELECT * FROM ' . POSTS_TABLE . ' WHERE ' . POST_CATEGORY_ID . ' = :id');
    $postsToUnassign->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $postsToUnassign->execute();

    // for all of the posts in that array
    foreach($postsToUnassign as $unassignRow) {
      $stnt = $pdo->prepare('UPDATE ' . POSTS_TABLE . ' SET ' . POST_CATEGORY_ID . " = '0' WHERE " . POST_ID . " = :rowId");
      $stnt->bindParam(':rowId', $unassignRow->{POST_ID}, PDO::PARAM_INT);
      $stnt->execute();
    }

    // delete ablum in album table
    $stnt = $pdo->prepare('DELETE FROM ' . CATEGORIES_TABLE . ' WHERE ' . CATEGORY_ID . " = :id");
    $stnt->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
    $stnt->execute();

    $header_success_msgs[] = "Album: '$delPostName' deleted (forevvverr).";
  }
}

include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
?>

<div class="content">

  <?php include "includes/header_msg_display.php"; ?>

    <h1 class="page-header"><i class="fas fa-folder-open"></i> Albums</h1>
    <div class="table">

          <?php
          $stnt = $pdo->prepare('SELECT * FROM ' . CATEGORIES_TABLE . ' ORDER BY ' . CATEGORY_ID . ' DESC LIMIT :this_page_first_result, :results_per_page');
          $stnt->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
          $stnt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
          $stnt->execute();

          // if there are no posts
          if($stnt->rowCount() <= 0) {
            echo "<h2 class='noPostsFoundMessage'>No albums found 😢</h2>";
          }

          // if there are posts
          else { ?>

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Title</th>
                  <th class="thumbnailColumn">Thumbnail</th>
                  <th class="wordCountColumn">Description Word Count</th>
                  <th class="numberOfPostsColumn">Number of Posts</th>
                  <th class="actionsColumn">Actions</th>

                </tr>
              </thead>
              <tbody>

            <?php foreach($stnt as $row){ ?>
              <tr>
                <td><b><?php echo $row->{CATEGORY_TITLE};?></b></td>
                <td class="thumbnailColumn" style="background-image: url('img/uploads/album_covers/<?php echo($row->{CATEGORY_FILENAME});?>'); background-size: contain; background-repeat: no-repeat;" ></td>
                <td class="wordCountColumn"><?php echo str_word_count($row->{CATEGORY_DESCRIPTION});?> words</td>


                <?php // fetch number of posts in this album, assign to $i
                $post_fetch_stnt = $pdo->prepare('SELECT * FROM ' . POSTS_TABLE . ' WHERE ' . POST_CATEGORY_ID . ' = :thisRowID');
                $post_fetch_stnt->bindParam(':thisRowID', $row->{CATEGORY_ID}, PDO::PARAM_INT);
                $post_fetch_stnt->execute();
                $i = $post_fetch_stnt->rowCount();
                ?>

                <td class="numberOfPostsColumn">
                  <b><?php echo "$i"; ?></b>
                </td>

                <td>
                  <a href="album_single.php?post=<?php echo $row->{CATEGORY_ID};?>" class="btn btnBlock">View</a>
                  <a href="backend_album_details.php?album=<?php echo $row->{CATEGORY_ID};?>" class="btn btnBlock">Details</a>
                  <a href="backend_edit_album.php?post=<?php echo $row->{CATEGORY_ID};?>" class="btn btn-warning btnBlock">Edit</a>
                  <a class="btn btn-danger btnBlock" onClick="javascript: return confirm('Please confirm deletion of this album. The posts within the album will not be deleted.');" href="backend_albums_view.php?action=delete&id=<?php echo $row->{CATEGORY_ID};?>">Delete</a>
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
      echo "<a class='backend_pagination_btn' href='backend_albums_view.php?page=$lastPage'><<</a>";
    }
    if ($number_of_pages > $thisPage) {
      $nextPage = $thisPage+1;
      echo "<a class='backend_pagination_btn' href='backend_albums_view.php?page=$nextPage'>>></a>";
    }

    echo "</div><br>";

    for ($page=1; $page <= $number_of_pages; $page++) {
      if ($page == $thisPage) {
        echo "<b class='backend_pagination current_page' href='backend_albums_view.php?page=$page'>$page</b>";
      }
      else {
        echo "<a class='backend_pagination' href='backend_albums_view.php?page=$page'>$page</a>";
      }
    }


    echo "</div>";
    ?>

    <br><br>
    <a class="btn" href="backend_new_album.php">Create Album</a>
  </div>

</body>
</html>
