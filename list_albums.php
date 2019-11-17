<?php

//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

//setting title
$title = "List of Albums";

# PAGINATION
// setting results per page //
$results_per_page = 6;

// finding num of pages //
$num_of_rows = $pdo->query("SELECT * FROM " . CATEGORIES_TABLE);
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
    header("location: list_albums.php?");
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
    <h1> All Albums </h1>
  </div>
  <?php

  //database query, fetching all posts, descending order, within the limits of the pagination
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
    <div class="alb_area_container"> <div class="alb_area">
      <?php foreach($stnt as $row){ ?>
        <a class="album_link"  href="album_single.php?post=<?php echo $row->{CATEGORY_ID};?>">
          <div class="album_link_img" style="background-image: url('img/uploads/album_covers/<?php echo $row->{CATEGORY_FILENAME};?>');"> </div>

          <?php
          // if the text is too long to dipslay all of it for the preview
          $Alb_Title_Preview =   $row->{CATEGORY_TITLE};
          if (strlen($Alb_Title_Preview) > 50) {
            $Alb_Title_Preview = wordwrap($Alb_Title_Preview, 50);
            $Alb_Title_Preview = explode("\n", $Alb_Title_Preview);
            $Alb_Title_Preview = $Alb_Title_Preview[0] . '...';
          } ?>
          <div class="album_link_text">  <h2 class="album_link_name"> <?php echo($Alb_Title_Preview); ?> </h2>


            <?php
            // if the text is too long to dipslay all of it for the preview
            $Alb_Desc_Preview =   $row->{CATEGORY_DESCRIPTION};
            if (strlen($Alb_Desc_Preview) > 500) {
              $Alb_Desc_Preview = wordwrap($Alb_Desc_Preview, 500);
              $Alb_Desc_Preview = explode("\n", $Alb_Desc_Preview);
              $Alb_Desc_Preview = $Alb_Desc_Preview[0] . '...';
            } ?>
            <p class="album_link_desc"> <?php echo($Alb_Desc_Preview); ?> </p>
          </div>
        </a>
      <?php } ?>
    </div>
  </div>
<?php } ?>
</div>




<div class='frontend_pagination_container'> <div class='frontend_pagination_btn_container'>

  <?php // pagination

  // Back button
  if ($thisPage > 1) {
    $lastPage = $thisPage-1;
    echo "<a class='frontend_pagination_btn' href='list_albums.php?page=$lastPage'><<</a>";
  }

  // Next Button
  if ($number_of_pages > $thisPage) {
    $nextPage = $thisPage+1;
    echo "<a class='frontend_pagination_btn' href='list_albums.php?page=$nextPage'>>></a>";
  }

  echo "</div><br>";

  // list pages
  for ($page=1; $page <= $number_of_pages; $page++) {
    if ($page == $thisPage) {
      echo "<b class='frontend_pagination current_page' href='list_albums.php?page=$page'>$page</b>";
    }
    else {
      echo "<a class='frontend_pagination' href='list_albums.php?page=$page'>$page</a>";
    }
  }
  ?>

</div>

<?php include("includes/footer.php"); ?>
