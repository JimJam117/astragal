<?php
include_once "includes/config.php";
include_once "includes/db.php";

// setting title
$title = "Home Setup";

// grabbing settings
$pref = $pdo->prepare("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
$pref->execute();
$pref = $pref->fetch();

// message arrays
$header_success_msgs = array();
$header_error_msgs = array();

// include the update functions
include_once "$dir/includes/upload.general_settings.inc.php";
include_once "$dir/includes/param_update.inc.php";
include_once "$dir/includes/dropdown_update.inc.php";

if (isset($_POST['submit'])) {
    $is_facebook_enabled = false;
    $is_email_enabled = false;
    $is_instagram_enabled = false;
    if (isset($_POST['bool_is_facebook_enabled'])) {
        $is_facebook_enabled = $_POST['bool_is_facebook_enabled'];
    }
    if (isset($_POST['bool_is_email_enabled'])) {
        $is_email_enabled = $_POST['bool_is_email_enabled'];
    }
    if (isset($_POST['bool_is_instagram_enabled'])) {
        $is_instagram_enabled = $_POST['bool_is_instagram_enabled'];
    }

    // set up the bool var for social links
    // facebook
    $is_facebook_enabled_string = "";
    if ($is_facebook_enabled == true) {
        $is_facebook_enabled_string = "1";
    } else {
        $is_facebook_enabled_string = "0";
    }
    // if db is not the same as string_var
    if ($pref->{SETTINGS_IS_FACEBOOK_ENABLED} != $is_facebook_enabled_string) {
        // if link is deactivated
        if ($is_facebook_enabled == false) {
            $a = '0';
            $header_success_msgs[] = "Successfully deactivated facebook link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_FACEBOOK_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
        // if link is activated
        elseif ($is_facebook_enabled == true) {
            $a = '1';
            $header_success_msgs[] = "Successfully activated facebook link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_FACEBOOK_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // email
    $is_email_enabled_string = "";
    if ($is_email_enabled == true) {
        $is_email_enabled_string = "1";
    } else {
        $is_email_enabled_string = "0";
    }
    // if db is not the same as string_var
    if ($pref->{SETTINGS_IS_EMAIL_ENABLED} != $is_email_enabled_string) {
        // if link is deactivated
        if ($is_email_enabled == false) {
            $a = '0';
            $header_success_msgs[] = "Successfully deactivated email link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_EMAIL_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
        // if link is activated
        elseif ($is_email_enabled == true) {
            $a = '1';
            $header_success_msgs[] = "Successfully activated email link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_EMAIL_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // instagram
    $is_instagram_enabled_string = "";
    if ($is_instagram_enabled == true) {
        $is_instagram_enabled_string = "1";
    } else {
        $is_instagram_enabled_string = "0";
    }
    // if db is not the same as string_var
    if ($pref->{SETTINGS_IS_INSTAGRAM_ENABLED} != $is_instagram_enabled_string) {
        // if link is deactivated
        if ($is_instagram_enabled == false) {
            $a = '0';
            $header_success_msgs[] = "Successfully deactivated instagram link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_INSTAGRAM_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
        // if link is activated
        elseif ($is_instagram_enabled == true) {
            $a = '1';
            $header_success_msgs[] = "Successfully activated instagram link!";

            $stmt = $pdo->prepare("UPDATE " . SETTINGS_TABLE . " SET " . SETTINGS_IS_INSTAGRAM_ENABLED . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
            $stmt->bindParam(':var', $a, PDO::PARAM_STR);
            $stmt->execute();
        }
    }



    // TEXT
    if (isset($_POST['about_section'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['about_section'], SETTINGS_ABOUT_TEXT, $pdo)) {
            $header_success_msgs[] = "Successfully updated about section!";
        } elseif (empty($_POST['about_section'])) {
            $header_error_msgs[] = "Error, no about section!";
        }
    }

    if (isset($_POST['landingpage_title'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['landingpage_title'], SETTINGS_LANDINGPAGE_TITLE, $pdo)) {
            $header_success_msgs[] = "Successfully updated landing page title!";
        } elseif (empty($_POST['landingpage_title'])) {
            $header_error_msgs[] = "Error, no landingpage title!";
        }
    }

    if (isset($_POST['landingpage_text'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['landingpage_text'], SETTINGS_LANDINGPAGE_TEXT, $pdo)) {
            $header_success_msgs[] = "Successfully updated landing page text!";
        } elseif (empty($_POST['landingpage_text'])) {
            $header_error_msgs[] = "Error, no landing page text!";
        }
    }

    // SOCIAL LINKS
    if (isset($_POST['facebook_link']) && $is_facebook_enabled) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['facebook_link'], SETTINGS_FACEBOOK, $pdo)) {
            $header_success_msgs[] = "Successfully updated facebook link!";
        } elseif (empty($_POST['facebook_link'])) {
            $header_error_msgs[] = "Error, no facebook link!";
        }
    }

    if (isset($_POST['email_address']) && $is_email_enabled) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['email_address'], SETTINGS_EMAIL, $pdo)) {
            $header_success_msgs[] = "Successfully updated email link!";
        } elseif (empty($_POST['email_address'])) {
            $header_error_msgs[] = "Error, no email link!";
        }
    }

    if (isset($_POST['instagram_link']) && $is_instagram_enabled) {

    // if the database is updated add success message, if post var is empty add error message
        if (string_update($_POST['instagram_link'], SETTINGS_INSTAGRAM, $pdo)) {
            $header_success_msgs[] = "Successfully updated instagram link!";
        } elseif (empty($_POST['instagram_link'])) {
            $header_error_msgs[] = "Error, no instagram link!";
        }
    }

    if (isset($_POST['Featured_Image_1_Gal_ID'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (int_update($_POST['Featured_Image_1_Gal_ID'], SETTINGS_FEATURED_IMAGE_ID_1, $pdo)) {
            $header_success_msgs[] = "Successfully updated featured image 1!";
        }
    }

    if (isset($_POST['Featured_Image_2_Gal_ID'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (int_update($_POST['Featured_Image_2_Gal_ID'], SETTINGS_FEATURED_IMAGE_ID_2, $pdo)) {
            $header_success_msgs[] = "Successfully updated featured image 2!";
        }
    }

    if (isset($_POST['Featured_Image_3_Gal_ID'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (int_update($_POST['Featured_Image_3_Gal_ID'], SETTINGS_FEATURED_IMAGE_ID_3, $pdo)) {
            $header_success_msgs[] = "Successfully updated featured image 3!";
        }
    }

    if (isset($_POST['number_of_recent_posts_to_display'])) {

    // if the database is updated add success message, if post var is empty add error message
        if (int_update($_POST['number_of_recent_posts_to_display'], SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY, $pdo)) {
            $header_success_msgs[] = "Successfully updated recent posts!";
        }
    }



    /////////////// featured image checks //////////////////////////
  /*if (pref->SETTIGNS_FEATURED1 != post_featured1) {
  $header_msgs[] = "success_msg5=Successfully reassigned featured image 1!";
  $id = mysqli_real_escape_string($db, $_POST['id']);
  $query = "UPDATE pref SET Featured_Image_1_Gal_ID = '$Featured_Image_1_Gal_ID'
  WHERE user_id = '1'";
  $db->query($query);
}

if ($p2['Featured_Image_2_Gal_ID'] != $Featured_Image_2_Gal_ID) {
$header_msgs[] = "success_msg6=Successfully reassigned featured image 2!";
$id = mysqli_real_escape_string($db, $_POST['id']);
$query = "UPDATE pref SET Featured_Image_2_Gal_ID = '$Featured_Image_2_Gal_ID'
WHERE user_id = '1'";
$db->query($query);
}

if ($p2['Featured_Image_3_Gal_ID'] != $Featured_Image_3_Gal_ID) {
$header_msgs[] = "success_msg7=Successfully reassigned featured image 3!";
$id = mysqli_real_escape_string($db, $_POST['id']);
$query = "UPDATE pref SET Featured_Image_3_Gal_ID = '$Featured_Image_3_Gal_ID'
WHERE user_id = '1'";
$db->query($query);
}


if($number_of_recent_posts_to_display === null) {
$header_msgs[] = "err_msg11=Error, no number of recent posts to display!";
}
else if ($p2['number_of_recent_posts_to_display'] != $number_of_recent_posts_to_display) {
$header_msgs[] = "success_msg11=Successfully updated number of recent posts to display!";
$id = mysqli_real_escape_string($db, $_POST['id']);
$query = "UPDATE pref SET number_of_recent_posts_to_display = '$number_of_recent_posts_to_display'
WHERE user_id = '1'";
$db->query($query);
}*/
}


include_once "includes/backend_header.php";
include_once "includes/backend_sidebar.php";
?>

<div class="content">

  <?php include "includes/header_msg_display.php"; ?>


      <h1 class="page-header"><i class="fas fa-home"></i> Homepage Setup</h1>

      <form method="post" enctype="multipart/form-data">
        <table class="form_table">
          <tr class="form-group">
            <td>
              <h3 class="sub-header"><i class="fas fa-font"></i> Landing Page Title</h3>
              <p>The big text on the landing page. Put a short message here, maybe a greeting or simply just your name, whatever you feel like.</p>
            </td>
            <td></td>
            <td><input class="form-control" type="text" name="landingpage_title" minlength="3" maxlength="35" value="<?php echo $pref->{SETTINGS_LANDINGPAGE_TITLE}?>"/></td>
          </tr>

          <tr><td> <br> </td></tr>

          <tr class="form-group">
            <td>
              <h3 class="sub-header"><i class="fas fa-quote-left"></i> Landing Page Text</h3>
              <p>Some text to go underneath the landing page title. Explain in a sentence or two what your blog is about.</p>
            </td>
            <td></td>
            <td><textarea class="form-control" type="text" maxlength="350" name="landingpage_text" ><?php echo $pref->{SETTINGS_LANDINGPAGE_TEXT}?></textarea></td>
          </tr>

          <tr class="form-group">
            <td>
              <h3 class="sub-header"><i class="fas fa-paragraph"></i> About Me Text</h3>
              <p>Enter in a longer description about you or your blog. Should be about a paragraph or two long ideally, with a maximum of 2000 characters.</p>
            </td>
            <td></td>
            <td><textarea class="form-control" type="text" minlength="50" maxlength="2000" name="about_section" ><?php echo $pref->{SETTINGS_ABOUT_TEXT}?></textarea></td>
          </tr>

          <tr><td><hr></td></tr> <!--Featured image inputs-->
          <tr class="form-group">
            <td>
              <h3 class="sub-header"><i class="fas fa-crown"></i> Featured Images</h3>
              <p>Here you can set certain posts to be featured on the frontend. There are 3 slots for featured images, but you don't have to use all (or any) of them if you don't want to. If no featured images are selected, then the featured image section will not display.</p>
            </td>
            <td></td>
          </tr>
          <tr class="form-group">
            <td><b class="sub-header">Featured Image 1</b>
              <br>
              <select name="Featured_Image_1_Gal_ID">
                <option value=0>None</option>
                <?php
                $assocFeatImgID = 0;
                $imgs = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID . " DESC");
                $imgs->execute();

                foreach ($imgs as $row) {
                    $val = $row->{POST_ID};
                    $name = $row->{POST_TITLE};
                    if ($val == $pref->{SETTINGS_FEATURED_IMAGE_ID_1}) {
                        $assocFeatImgID = $val;
                        echo "<option value='$val' selected>$name</option>";
                    } else {
                        echo "<option value='$val'>$name</option>";
                    }
                }
                ?>
              </select>
            </td>
          </tr>

          <tr class="form-group">
            <td><b class="sub-header">Featured Image 2</b>
              <br>
              <select name="Featured_Image_2_Gal_ID">
                <option value=0>None</option>
                <?php
                $assocFeatImgID = 0;

                $imgs = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID . " DESC");
                $imgs->execute();

                foreach ($imgs as $row) {
                    $val = $row->{POST_ID};
                    $name = $row->{POST_TITLE};
                    if ($val == $pref->{SETTINGS_FEATURED_IMAGE_ID_2}) {
                        $assocFeatImgID = $val;
                        echo "<option value='$val' selected>$name</option>";
                    } else {
                        echo "<option value='$val'>$name</option>";
                    }
                }
                ?>
              </select>
            </td>
          </tr>

          <tr class="form-group">
            <td><b class="sub-header">Featured Image 3</b>
              <br>
              <select name="Featured_Image_3_Gal_ID">
                <option value=0>None</option>
                <?php
                $assocFeatImgID = 0;

                $imgs = $pdo->prepare("SELECT * FROM " . POSTS_TABLE . " ORDER BY " . POST_ID . " DESC");
                $imgs->execute();

                foreach ($imgs as $row) {
                    $val = $row->{POST_ID};
                    $name = $row->{POST_TITLE};
                    if ($val == $pref->{SETTINGS_FEATURED_IMAGE_ID_3}) {
                        $assocFeatImgID = $val;
                        echo "<option value='$val' selected>$name</option>";
                    } else {
                        echo "<option value='$val'>$name</option>";
                    }
                }
                ?>
              </select>
            </td>
          </tr>

          <tr><td><hr></td></tr> <!--Featured image inputs end-->

          <tr class="form-group">
            <td><h3><i class="fas fa-calendar-day"></i> Number of Recent Posts to Display</h3><p>Can be set between 0 and 6, set to 0 to not display recent posts section</p>
              <br>
              <input class="number_input" type="number" name="number_of_recent_posts_to_display" min="0" max="6" value="<?php echo $pref->{SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY}?>"></td>

            </tr>

            <tr><td><hr></td></tr> <!--Featured image recent posts-->

            <tr class="form-group">
              <td>
                <h3><i class="fas fa-id-badge"></i> Contact Details</h3>
                <p>Provide your contact details here so your fans can get in touch with you (or just stalk you).</p>
              </td>
            </tr>
            <tr><td> <br> </td></tr>

            <tr>
              <td>
                <?php
                if ($pref->{SETTINGS_IS_FACEBOOK_ENABLED} == true) {
                    $is_facebook_enabled = true; ?>
                  <label class='checkbox_container'> <b class='sub-header'><i class='fab fa-facebook-square'></i> Facebook Link</b>
                    <input type="checkbox" name="bool_is_facebook_enabled" checked onclick="var input = document.getElementById('fb_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } else {
                    $is_facebook_enabled = false; ?>
                  <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-facebook-square"></i> Facebook Link</b>
                    <input type="checkbox" name="bool_is_facebook_enabled" onclick="var input = document.getElementById('fb_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } ?>
              </td>
              <td></td>
              <td>
                <?php // if checked, display enabled input
                if ($is_facebook_enabled == true) { ?>
                  <input id="fb_link" class="form-control" type="text" name="facebook_link" minlength="15" maxlength="1000" value="<?php echo $pref->{SETTINGS_FACEBOOK}?>"/>
                <?php } else { ?>
                  <input id="fb_link" disabled="disabled" class="form-control" type="text" name="facebook_link" minlength="15" maxlength="1000" value="<?php echo $pref->{SETTINGS_FACEBOOK}?>"/>
                <?php } ?>

              </td>
            </tr>

            <tr class="form-group">
              <td>
                <?php if ($pref->{SETTINGS_IS_EMAIL_ENABLED} == true) {
                    $is_email_enabled = true; ?>
                  <label class="checkbox_container"><b class="sub-header"><i class="fas fa-envelope-square"></i> Email Address</b>
                    <input type="checkbox" checked name="bool_is_email_enabled" onclick="var input = document.getElementById('email'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } else {
                    $is_email_enabled = false; ?>
                  <label class="checkbox_container"><b class="sub-header"><i class="fas fa-envelope-square"></i> Email Address</b>
                    <input type="checkbox" name="bool_is_email_enabled" onclick="var input = document.getElementById('email'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } ?>


              </td>
              <td></td>
              <td>
                <?php // if checked, display enabled input
                if ($is_email_enabled == true) { ?>
                  <input id="email" class="form-control" type="text" name="email_address" minlength="3" maxlength="70" value="<?php echo $pref->{SETTINGS_EMAIL}?>"/>
                <?php } else { ?>
                  <input id="email" disabled="disabled" class="form-control" type="text" name="email_address" minlength="3" maxlength="70" value="<?php echo $pref->{SETTINGS_EMAIL}?>"/>
                <?php } ?>
              </td>
            </tr>

            <tr class="form-group">
              <td>
                <?php if ($pref->{SETTINGS_IS_INSTAGRAM_ENABLED} == true) {
                    $is_instagram_enabled = true; ?>
                  <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-instagram"></i> Instagram Link</b>
                    <input type="checkbox" checked name="bool_is_instagram_enabled" onclick="var input = document.getElementById('instagram_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } else {
                    $is_instagram_enabled = false; ?>
                  <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-instagram"></i> Instagram Link</b>
                    <input type="checkbox" name="bool_is_instagram_enabled" onclick="var input = document.getElementById('instagram_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
                    <span class="checkmark"></span>
                  </label>
                <?php
                } ?>
              </td>
              <td></td>
              <td>
                <?php // if checked, display enabled input
                if ($is_instagram_enabled == true) { ?>
                  <input id="instagram_link" class="form-control" type="text" name="instagram_link" minlength="15" maxlength="1000" value="<?php echo $pref->{SETTINGS_INSTAGRAM}?>"/></td>
                <?php } else { ?>
                  <input id="instagram_link" disabled="disabled" class="form-control" type="text" name="instagram_link" minlength="15" maxlength="1000" value="<?php echo $pref->{SETTINGS_INSTAGRAM}?>"/></td>
                <?php } ?>
              </tr>

              <td>
                <div class="form-group">
                  <button class="btn" name="submit" type="submit">Update</button>
                </div>
              </td>
            </tr>
          </table>
          <br>
        </form>
        <br><br>
      </div>
    </body>
    </html>
