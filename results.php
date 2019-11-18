<?php
include_once ("includes/config.php");
include_once ("includes/db.php");

// Search Results Stuff

$title = "Search Results";
$results = "";
$keyword = "";

// if search is set
if(isset($_GET['search'])) {

  // setting search input
  $search_input = $_GET['search'];

  // if search input is null, display all
  if($search_input == null) {
    $results = 'Search Results';
  }
  else{
    $results = 'Search Results for "' . $search_input . '"';
  }

  // keyword is search input with % either side
  $search_input = strtolower($search_input);
  $keyword = "%" . $search_input . "%";


  // grab keyword and find it in posts and albums tables
  $posts = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE lower(" . POST_TITLE . ") LIKE :keyword OR lower(" . POST_DESCRIPTION . ") LIKE :keyword ORDER BY " . POST_ID . " DESC");
  $posts->bindParam(':keyword', $keyword, PDO::PARAM_STR);
  $posts->execute();

  $albums = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE lower(" . CATEGORY_TITLE . ") LIKE :keyword OR lower(" . CATEGORY_DESCRIPTION . ") LIKE :keyword ORDER BY " . CATEGORY_ID . " DESC");
  $albums->bindParam(':keyword', $keyword, PDO::PARAM_STR);
  $albums->execute();

  // pagination stuff
  $results_per_page = 6;

  $num_of_rows_in_albums = $albums->rowCount();
  $num_of_rows_in_posts = $posts->rowCount();

  $number_of_pages_for_albums = ceil($num_of_rows_in_albums / $results_per_page);
  $number_of_pages_for_posts = ceil($num_of_rows_in_posts / $results_per_page);


  // assign the bigger number to $number_of_pages
  $number_of_pages = 1;
  if($number_of_pages_for_posts > $number_of_pages_for_albums){
    $number_of_pages = $number_of_pages_for_posts;
  }
  else{
    $number_of_pages = $number_of_pages_for_albums;
  }

  if(!isset($_GET['page'])){
    $thisPage = 1;
  }
  else {
    if($_GET['page'] > $number_of_pages || $_GET['page'] < 0) {
      $thisPage = 1;
      header("location: results.php?search=$search_input");
    }
    $thisPage = $_GET['page'];
  }
  $this_page_first_result = ($thisPage-1)*$results_per_page;

  // pagination end


  // redo the queries with pagination in mind
  $posts = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE lower(" . POST_TITLE . ") LIKE :keyword OR lower(" . POST_DESCRIPTION . ") LIKE :keyword ORDER BY " . POST_ID . " DESC LIMIT :this_page_first_result, :results_per_page");
  $posts->bindParam(':keyword', $keyword, PDO::PARAM_STR);
  $posts->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
  $posts->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $posts->execute();

  $albums = $pdo->prepare("SELECT * FROM " . CATEGORIES_TABLE . " WHERE lower(" . CATEGORY_TITLE . ") LIKE :keyword OR lower(" . CATEGORY_DESCRIPTION . ") LIKE :keyword ORDER BY " . CATEGORY_ID . " DESC LIMIT :this_page_first_result, :results_per_page");
  $albums->bindParam(':keyword', $keyword, PDO::PARAM_STR);
  $albums->bindParam(':this_page_first_result', $this_page_first_result, PDO::PARAM_INT);
  $albums->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
  $albums->execute();

  $title = "$results";
}
// if search isn't set
else{ header("location: 404.php?err_msg=No search given!"); exit(); }


include_once ("includes/header.php");
?>

<div class="mainGallery">
  <!--Main title-->
  <div class="title_area">
    <h1 class="searchOutput"><?php echo $results;?></h1>
  </div>

  <!--search Container, using same styles as album page-->
  <div class="alb_area_container">
    <div class="alb_area">
      <?php
      if($posts->rowCount() == 0 && $thisPage != 1) { ?>
        <br>
        <br>
        <?php
      }

      // if no posts
      else if ($posts->rowCount() == 0) {
        ?>
        <div class='noResults-banner'>
          <h3 class="ResultsBanner">No post results found 😢</h3>
          <br>
        </div>
        <br>
        <br>
        <?php
      }
      // if there are posts
      else {
        echo "<h3 class='ResultsBanner'>Posts</h3>";
        foreach ($posts as $row){
          ?>
          <a class="album_link" href="single.php?post=<?php echo $row->{POST_ID}; ?>">
            <div class="album_link_img" style="background-image: url('img/uploads/<?php echo $row->{POST_FILENAME}; ?>');" alt="<?php echo $row->{POST_TITLE}; ?>"> </div>
            <div class="album_link_text">
              <h2 class="album_link_name"> <?php echo $row->{POST_TITLE}; ?> </h2>
              <div class="album_link_desc">
                <?php
                // if the text is too long to dipslay all of it for the preview
                $Gal_Desc_Preview =   $row->{POST_DESCRIPTION};
                if (strlen($Gal_Desc_Preview) > 500) {
                  $Gal_Desc_Preview = wordwrap($Gal_Desc_Preview, 500);
                  $Gal_Desc_Preview = explode("\n", $Gal_Desc_Preview);
                  $Gal_Desc_Preview = $Gal_Desc_Preview[0] . '...';
                }
                echo "$Gal_Desc_Preview"; ?>
              </div>
            </div>
          </a>
          <?php
        }
      }

      if($albums->rowCount() == 0 && $thisPage != 1) { ?>
        <br>
        <br>
        <?php
      }
      // if no albums
      else if ($albums->rowCount() == 0) {
        ?>
        <div class='noResults-banner'>
          <h3 class="ResultsBanner">No album results found 😢</h3>
          <br>
        </div>
        <br>
        <br>
        <?php
      }
      // if there are albums
      else{
        echo "<h3 class='ResultsBanner'>Albums</h3>";
        foreach ($albums as $row){
          ?>
          <a class="album_link" href="album_single.php?post=<?php echo $row->{CATEGORY_ID}; ?>">
            <div class="album_link_img" style="background-image: url('img/uploads/album_covers/<?php echo $row->{CATEGORY_FILENAME}; ?>');" alt="<?php echo $row->{CATEGORY_TITLE}; ?>"> </div>
            <div class="album_link_text">
              <h2 class="album_link_name"> <?php echo $row->{CATEGORY_TITLE}; ?> </h2>
              <div class="album_link_desc">
                <?php
                // if the text is too long to dipslay all of it for the preview
                $Gal_Desc_Preview =   $row->{CATEGORY_DESCRIPTION};
                if (strlen($Gal_Desc_Preview) > 500) {
                  $Gal_Desc_Preview = wordwrap($Gal_Desc_Preview, 500);
                  $Gal_Desc_Preview = explode("\n", $Gal_Desc_Preview);
                  $Gal_Desc_Preview = $Gal_Desc_Preview[0] . '...';
                }
                echo "$Gal_Desc_Preview"; ?>
              </div>
            </div>
          </a>
          <?php
        }
      } ?>

    </div> <!--alb_area end -->
  </div> <!--alb_area_container end -->
</div> <!--mainGallery end -->


<?php
// pagination
echo "<div class='frontend_pagination_container'> <div class='frontend_pagination_btn_container'>";

if ($thisPage > 1) {
  $lastPage = $thisPage-1;
  echo "<a class='frontend_pagination_btn' href='results.php?search=$search_input&page=$lastPage'><<</a>";
}
if ($number_of_pages > $thisPage) {
  $nextPage = $thisPage+1;
  echo "<a class='frontend_pagination_btn' href='results.php?search=$search_input&page=$nextPage'>>></a>";
}

echo "</div><br>";

for ($page=1; $page <= $number_of_pages; $page++) {
  if ($page == $thisPage) {
    echo "<b class='frontend_pagination current_page' href='results.php?search=$search_input&page=$page'>$page</b>";
  }
  else {
    echo "<a class='frontend_pagination' href='results.php?search=$search_input&page=$page'>$page</a>";
  }
}

echo "</div>";
?>

<?php include("includes/footer.php"); ?>
