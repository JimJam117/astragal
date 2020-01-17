<?php

//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

// if post is set
if(isset($_GET['post'])) {
  $stmt = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE " . CATEGORY_ID . "= :id");
  $stmt->bindParam(':id', $_GET['post'], PDO::PARAM_INT);
  $stmt->execute();

  // if there are no posts
  if($stmt->rowCount() <= 0) {
    header("Location:404.php?err_msg=Error, album not found :(");
    exit();
  }
  // if there is a post, fetch it
  else{
    $row = $stmt->fetch();
    $title = $row->{CATEGORY_TITLE};
  }
}

// if post value is not given in url, exit
else{
  header("Location:404.php?err_msg=Error, album not defined :(");
  exit();
}

# PAGINATION
// setting results per page //
$results_per_page = 12;

// finding num of pages //
$num_of_rows = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " .  POST_CATEGORY_ID . "= :cat_id");
$num_of_rows->bindParam(':cat_id', $row->{CATEGORY_ID}, PDO::PARAM_INT);
$num_of_rows->execute();

$number_of_pages = ceil($num_of_rows->rowCount() / $results_per_page);

// if page is not set
if(!isset($_GET['page'])){
  $thisPage = 1;
}
else {
  // if the page number is too great or less than zero, return to page 1
  if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
    $thisPage = 1;
    header("location: album_single.php?post=" . $row->{CATEGORY_ID});
  }
  // set this page to page in url
  $thisPage = $_GET['page'];
}

// The first result on the given page
$this_page_first_result = ($thisPage-1)*$results_per_page;

// include header
include_once ("includes/header.php");
?>

<div class="mainGallery">
  <div class="title_area">
    <!--MAIN TITLE-->
    <h1> <?php echo($row->{CATEGORY_TITLE});?> </h1>
  </div>
  <div class="album_single_container">
  <div class="album_single_area">
    <img src="img/uploads/album_covers/<?php echo($row->{CATEGORY_FILENAME});?>">
    <div class="album_single_desc"><?php echo($row->{CATEGORY_DESCRIPTION});?></div>
  </div>
  <div class="album_posts_section_header">
    <?php
    // this is to limit the size of the title in the "posts within" section

    $Alb_Title_Preview =   $row->{CATEGORY_TITLE};
    if (strlen($Alb_Title_Preview) > 50) {
      $Alb_Title_Preview = wordwrap($Alb_Title_Preview, 50);
      $Alb_Title_Preview = explode("\n", $Alb_Title_Preview);
      $Alb_Title_Preview = $Alb_Title_Preview[0] . '...';
    }
    ?>
    Posts within <span class="italic"><?php echo($Alb_Title_Preview);?></span>
  </div>

  <?php

  $posts_stmt = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_CATEGORY_ID . "= :cat_id ORDER BY " . POST_ID . " DESC LIMIT :this_page_first_result, :results_per_page");
  $posts_stmt->bindParam(':cat_id', $row->{CATEGORY_ID}, PDO::PARAM_INT);
  $posts_stmt->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
  $posts_stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $posts_stmt->execute();

  // if there are no posts
  if($posts_stmt->rowCount() <= 0) {
  echo "<h2 class='noPostsFoundMessage noPostsFoundMessage_albumSingle'>No posts found, album is empty 😢</h2>";
  }
  // if there are posts, display them
  else{
    echo "<div class='gal_area'>";
    foreach ($posts_stmt as $post_row){?>
      <a style="background-image: url('img/uploads/<?php echo $post_row->{POST_FILENAME};?>');" class="image_link"  href="single.php?post=<?php echo $post_row->{POST_ID}; ?>">
        <div class="filter">
          <?php
          ?> <h2 class="name name_album_single"> <?php echo $post_row->{POST_TITLE}; ?> </h2> <?php
          ?>
        </div>
        </a>
        <?php } ?>
      </div> <!--gal_area end -->
    <?php } // else end ?>
  </div> <!--album_single_container end -->
</div> <!--mainGallery end -->

<?php // pagination
echo "<div class='frontend_pagination_container'> <div class='frontend_pagination_btn_container'>";

if ($thisPage > 1) {
  $lastPage = $thisPage-1;
  echo "<a class='frontend_pagination_btn' href='album_single.php?post=" . $row->{CATEGORY_ID} . "&page=$lastPage'><<</a>";
}
if ($number_of_pages > $thisPage) {
  $nextPage = $thisPage+1;
  echo "<a class='frontend_pagination_btn' href='album_single.php?post=" . $row->{CATEGORY_ID} . "&page=$nextPage'>>></a>";
}

echo "</div><br>";

for ($page=1; $page <= $number_of_pages; $page++) {
  if ($page == $thisPage) {
    echo "<b class='frontend_pagination current_page' href='album_single.php?post=" . $row->{CATEGORY_ID} . "&page=$page'>$page</b>";
  }
  else {
    echo "<a class='frontend_pagination' href='album_single.php?post=" . $row->{CATEGORY_ID} . "&page=$page'>$page</a>";
  }
}
echo "</div>";
?>

<?php include("includes/footer.php"); ?>
