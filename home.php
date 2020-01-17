<?php

//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");

// include header
include_once ("includes/header.php");

$pref = $pdo->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref = $pref->fetch();

$number_of_recent_posts_to_display = $pref->{SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY};
$FeaturedImage1 = $pref->{SETTINGS_FEATURED_IMAGE_ID_1};
$FeaturedImage2 = $pref->{SETTINGS_FEATURED_IMAGE_ID_2};
$FeaturedImage3 = $pref->{SETTINGS_FEATURED_IMAGE_ID_3};


// Checks for if each section is displaying, sets a boolean for each
$FeaturedImageIsDisplaying = true;
$RecentPostsIsDisplaying = true;
$AboutSectionIsDisplaying = true;
$FacebookIsDisplaying = true;
$EmailIsDisplaying = true;
$InstagramIsDisplaying = true;

// determine if the featured image section is displaying
if($FeaturedImage1 == 0 && $FeaturedImage2 == 0 && $FeaturedImage3 == 0) {
  $FeaturedImageIsDisplaying = false;
}
else{
  $FeaturedImageIsDisplaying = true;
}

// looking at the database, making sure there is at least 1 post in the database
$recentPostsCheck = $pdo->query("SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID . " DESC");

// determine if the recent posts section is displaying
if($number_of_recent_posts_to_display <= 0 || $recentPostsCheck->rowCount() <= 0){
  $RecentPostsIsDisplaying = false;
}
else{
  $RecentPostsIsDisplaying = true;
}

// determine if the about section is displaying
if(empty($pref->{SETTINGS_ABOUT_TEXT})){
  $AboutSectionIsDisplaying = false;
}
else{
  $AboutSectionIsDisplaying = true;
}

// determine if the facebook link is displaying
if(empty($pref->{SETTINGS_FACEBOOK}) || $pref->{SETTINGS_IS_FACEBOOK_ENABLED} == "0"){
  $FacebookIsDisplaying = false;
}
else{
  $FacebookIsDisplaying = true;
}

// determine if the email button is displaying
if(empty($pref->{SETTINGS_EMAIL}) || $pref->{SETTINGS_IS_EMAIL_ENABLED} == "0"){
  $EmailIsDisplaying = false;
}
else{
  $EmailIsDisplaying = true;
}

// determine if the instagram link is displaying
if(empty($pref->{SETTINGS_INSTAGRAM}) || $pref->{SETTINGS_IS_INSTAGRAM_ENABLED} == "0"){
  $InstagramIsDisplaying = false;
}
else{
  $InstagramIsDisplaying = true;
}


function FeaturedImageDisplay($FeaturedImageInput, $pdo_obj) {
  if($FeaturedImageInput !== 0) {
    $stmt = $pdo_obj->prepare("SELECT * FROM " . POSTS_TABLE . " WHERE " . POST_ID . " = :FeaturedImageInput");
    $stmt->execute(['FeaturedImageInput' => $FeaturedImageInput]);
    $post = $stmt->fetch();
    if(!empty($post)) { ?>
      <a style="background-image: url('img/uploads/<?php echo $post->{POST_FILENAME}; ?>');" class="image_link"  href="single.php?post=<?php echo $post->{POST_ID} ;?>">
        <div class="filter">
          <?php
          ?> <h2 class="name"> <?php echo $post->{POST_TITLE}; ?> </h2> <?php
          ?> </div></a><?php
        }
      }
    }

    ?>


    <div class="landingPage">
      <?php if (!empty($pref->{SETTINGS_LANDINGPAGE_TITLE})) { ?>
        <h1 data-aos="zoom-out" class="landingPage_title"><?php echo $pref->{SETTINGS_LANDINGPAGE_TITLE}; ?></h1>
      <?php } ?>
      <div data-aos="zoom-out" class="landingpage_text">
        <br>
        <?php if (!empty($pref->{SETTINGS_LANDINGPAGE_TEXT})) { echo "<p>" . $pref->{SETTINGS_LANDINGPAGE_TEXT} . "</p>"; } ?>
      </div>
    </div>


    <div class="homePageSectionBk">
      <div class="homePageSection">

        <h2 class='SectionTitle'>Explore</h2>
        <div data-aos="fade-up" class="mainLinksSection">
          <a href="list_albums.php" class="mainLinksSection_div">
            <h2>View Albums</h2>
            <p>Click here to explore all of my albums</p>
            <br>
            <i class="fas fa-folder-open"></i>
          </a>

          <a href="list_posts.php" class="mainLinksSection_div">
            <h2>View All Posts</h2>
            <p>Click here to explore all of my posts</p>
            <br>
            <i class="fas fa-images"></i>
          </a>
        </div>

        <?php if($FeaturedImageIsDisplaying) { ?>
          <h2 data-aos="fade-left" class='SectionTitle'>Featured Works</h2>
          <div data-aos="fade-up" class="featuredImages">
            <div class="miniGallery">
              <?php
              FeaturedImageDisplay($FeaturedImage1, $pdo);
              FeaturedImageDisplay($FeaturedImage2, $pdo);
              FeaturedImageDisplay($FeaturedImage3, $pdo);
              ?>
            </div>
          </div>
        <?php } //"featured image if" end ?>


        <?php if($RecentPostsIsDisplaying) {
          if($number_of_recent_posts_to_display == 1)
          {
            echo "<h2 data-aos='fade-right' class='SectionTitle'>Most Recent Upload</h2>";
          }
          elseif($number_of_recent_posts_to_display > 1)
          {
            echo "<h2 data-aos='fade-right' class='SectionTitle'>Recent Uploads</h2>";
          }
          ?>

          <div data-aos="fade-up" class="featuredImages recentPosts">
            <div class="miniGallery">
              <?php
              $query ="SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID . " DESC";
              $posts = $pdo->query($query);

              if($posts->rowCount() > 0) {
                $i = 0;
                while($row = $posts->fetch() and $i < $pref->{SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY}) {
                  $i++;
                  ?>
                  <a style="background-image: url('img/uploads/<?php echo $row->{POST_FILENAME};?>');" class="image_link"  href="single.php?post=<?php echo $row->{POST_ID};?>">
                    <div class="filter">
                      <?php
                      ?> <h2 class="name"> <?php echo $row->{POST_TITLE}; ?> </h2> <?php
                      ?>  </div></a><?php
                    }
                  }
                  ?>

                </div>
              </div>
              <a class="viewMoreBtn" href="list_posts.php">View More...</a>
            <?php } ?>

            <?php if($AboutSectionIsDisplaying) { ?>
              <h2 class='SectionTitle' data-aos="fade-up">About Me</h2>
              <div data-aos="fade-up" class="aboutSection">
                <img src="<?php echo "img/uploads/pref/" . $pref->{SETTINGS_PROFILE_IMAGE_LOCATION}; ?>" alt="Hey look it me">
                <p><?php echo $pref->{SETTINGS_ABOUT_TEXT}; ?></p>

              </div>

            <?php } ?>
            <br><br>


            <!-- Social Links -->
            <span id="tooltiptext_email" class="tooltiptext"><?php echo $pref->{SETTINGS_EMAIL}; ?></span>

            <div class="endSection">

              <?php if($FacebookIsDisplaying) { ?>
                <button data-aos="fade-up" data-aos-duration="2000" onclick="location.href='<?php echo $pref->{SETTINGS_FACEBOOK}; ?>';"
                  type="button" class="social_button social_button_facebook"
                  name="button"><i class="fab fa-facebook-f"></i></button>
                <?php } ?>

                <?php if($EmailIsDisplaying) { ?>
                  <button data-aos="fade-up" data-aos-duration="2000" onclick="emailDisplay();"
                  type="button" class="social_button social_button_email"
                  name="button"><i class="fas fa-envelope"></i></button>
                <?php } ?>

                <?php if($InstagramIsDisplaying) { ?>
                  <button data-aos="fade-up" data-aos-duration="2000" onclick="location.href='<?php echo $pref->{SETTINGS_INSTAGRAM}; ?>';"
                    type="button" class="social_button social_button_instagram"
                    name="button"><i class="fab fa-instagram"></i></button>
                  <?php } ?>
                </div>


                <!-- Footer -->
                <div class="contactSection">
                  <hr>
                  <?php
                  if($EmailIsDisplaying) {
                    echo $pref->{SETTINGS_EMAIL};
                    echo " <br><br> <a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
                  }
                  else {
                    echo "<a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
                  }
                  ?>

                </div>




              </div>

            </div>

          </div>
          <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
          <script>
          AOS.init({once: true});
          </script>
        </body>


        </html>
