<?php

//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

//setting title
$title = "List of Posts";

# PAGINATION
// setting results per page //
$results_per_page = 24;

// finding num of pages //
$num_of_rows = $pdo->query("SELECT * FROM " . POSTS_TABLE);
$num_of_rows = $num_of_rows->rowCount();
$number_of_pages = ceil($num_of_rows / $results_per_page);

// if page is not set
if(!isset($_GET['page'])){
  $thisPage = 1;
}
else {
  // if the page number is too great or less than zero, return to page 1
  if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
    $thisPage = 1;
    header("location: list_posts.php?");
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
    <h1> All Posts </h1>
  </div>

  <?php

  //database query, fetching all posts, descending order, within the limits of the pagination
  $stnt = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID ." DESC LIMIT :this_page_first_result, :results_per_page");
  $stnt->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
  $stnt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $stnt->execute();

  // if there are no posts
  if($stnt->rowCount() <= 0) {
    echo "<h2 class='noPostsFoundMessage'>No posts found 😢</h2>";
  }

  // if there are posts
  else { ?>
    <div class="gal_area_container"><div class="gal_area">
      <?php foreach($stnt as $row){ ?>
        <a style="background-image: url('img/uploads/<?php echo $row->{POST_FILENAME};?>');" class="image_link"  href="single.php?post=<?php echo $row->{POST_ID};?>">
          <div class="filter">
            <?php
            ?> <h2 class="name"> <?php echo $row->{POST_TITLE}; ?> </h2> <?php
            ?> <p class="desc"> <?php echo $row->{POST_DESCRIPTION}; ?> </p>
          </div>
        </a>
      <?php } // foreach end ?>
    </div>
  </div>
<?php } // else end ?>
</div>




<div class='frontend_pagination_container'> <div class='frontend_pagination_btn_container'>

  <?php // pagination

  // Back button
  if ($thisPage > 1) {
    $lastPage = $thisPage-1;
    echo "<a class='frontend_pagination_btn' href='list_posts.php?page=$lastPage'><<</a>";
  }

  // Next Button
  if ($number_of_pages > $thisPage) {
    $nextPage = $thisPage+1;
    echo "<a class='frontend_pagination_btn' href='list_posts.php?page=$nextPage'>>></a>";
  }

  echo "</div><br>";

  // list pages
  for ($page=1; $page <= $number_of_pages; $page++) {
    if ($page == $thisPage) {
      echo "<b class='frontend_pagination current_page' href='list_posts.php?page=$page'>$page</b>";
    }
    else {
      echo "<a class='frontend_pagination' href='list_posts.php?page=$page'>$page</a>";
    }
  }

  ?>

</div>

<?php include("includes/footer.php"); ?>
