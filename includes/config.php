<?php
/*
There are 4 tables required in your database for this application to work: POSTS, CATEGORIES, ADMIN and SETTINGS.
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dir = "/var/www/astragal";
define('ROOTPATH', __DIR__);

/////////////////////////////////////////////////////////
### DEFINE DATABASE CONNECTION DETAILS ###
/////////////////////////////////////////////////////////
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "password");
define("DB_NAME", "astragal");

/////////////////////////////////////////////////////////
### DEFINE APP DETAILS ###
/////////////////////////////////////////////////////////
$APPNAME = "astragal";
$APPAUTHOR = "James Sparrow";
$APPVERSION = "1.01";
$APPAUTHORADDRESS = "https://www.jsparrow.uk";

/////////////////////////////////////////////////////////
### DEFINE TABLES ###
/////////////////////////////////////////////////////////

# POSTS_TABLE - This is the table which will include all of your posts.
define("POSTS_TABLE", "gal");

# CATEGORIES_TABLE - This is the table which will include all of your categories.
define("CATEGORIES_TABLE", "albums");

# ADMIN_TABLE - This is the table which will include the username and password of the admin, so that they can login to the app.
define("ADMIN_TABLE", "admin");

# SETTINGS_TABLE - This table will contain all of the settings for the app, such as whether or not certain features are turned on or off, the app's title and subtitle, and other similar settings. Please note that there should be only one row in this table, and it's SETTINGS_PROFILE_ID (the primary index) should be set to 1.
define("SETTINGS_TABLE", "pref");


/////////////////////////////////////////////////////////
### POSTS TABLE ###
/////////////////////////////////////////////////////////

# POST_ID - Int, Primary Index
// This is the Primary index for the table. It needs to be set up to auto increment.
define("POST_ID", "Gal_ID");

# POST_TITLE - longtext
// This is the post's title.
define("POST_TITLE", "Gal_Title");

# POST_DESCRIPTION - longtext
// This is the post's description.
define("POST_DESCRIPTION", "Gal_Desc");

# POST_FILENAME - longtext
// This is the post's header image file name.
define("POST_FILENAME", "Gal_FileName");

# POST_CATEGORY_ID - Int
// This is the Category ID for the associated category. Should be set to 0 if the post does not belong to a categroy.
define("POST_CATEGORY_ID", "Gal_This_Post_Album_ID");

/////////////////////////////////////////////////////////
### CATEGORIES TABLE ###
/////////////////////////////////////////////////////////

# CATEGORY_ID - Int, Primary Index
// This is the Primary index for the table. It needs to be set up to auto increment.
define("CATEGORY_ID", "Alb_ID");

# CATEGORY_TITLE - longtext
// This is the category's title.
define("CATEGORY_TITLE", "Alb_Title");

# CATEGORY_DESCRIPTION - longtext
// This is the category's description.
define("CATEGORY_DESCRIPTION", "Alb_Desc");

# CATEGORY_FILENAME - longtext
// This is the category's header image file name.
define("CATEGORY_FILENAME", "Alb_Cover_FileName");

/////////////////////////////////////////////////////////
### ADMIN TABLE ###
/////////////////////////////////////////////////////////

# ADMIN_ID - Int, Primary Index
// This is the Primary index for the table. It needs to be set up to auto increment.
define("ADMIN_ID", "user_id");

# ADMIN_USERNAME - varchar(256)
// This is the username for the given admin.
define("ADMIN_USERNAME", "user_name");

# ADMIN_PASSWORD - varchar(256)
// This is the pasword for the given admin.
define("ADMIN_PASSWORD", "user_password");


/////////////////////////////////////////////////////////
### SETTINGS TABLE ###
/////////////////////////////////////////////////////////

# SETTINGS_PROFILE_ID - Int, Primary Index
// This is the Primary index for the table. This selects the one and only row in this table, which should have the value of 1 to work properly.
define("SETTINGS_PROFILE_ID", "user_id");

# SETTINGS_TITLE - varchar(128)
// This is the title of the blog that will appear on the header or sidebar.
define("SETTINGS_TITLE", "main_text");

# SETTINGS_SUBTITLE - varchar(128)
// This is the sub-title of the blog that will appear on the header or sidebar.
define("SETTINGS_SUBTITLE", "sub_text");

# SETTINGS_BK_IMAGE_LOCATION - varchar(512)
// This is the filename of the background image used throughout the blog. Please note that this value should only be the file's name and extention, e.g. "background-image.jpeg"
define("SETTINGS_BK_IMAGE_LOCATION", "background_image_location");

# SETTINGS_PROFILE_IMAGE_LOCATION - varchar(512)
// This is the filename of the profile image used on the header or sidebar. Please note that this value should only be the file's name and extention, e.g. "profile-image.jpeg"
define("SETTINGS_PROFILE_IMAGE_LOCATION", "profile_pic_location");

# SETTINGS_LANDINGPAGE_TITLE - longtext
// This is the title of the landing page that will appear on the homepage of the blog.
define("SETTINGS_LANDINGPAGE_TITLE", "landingpage_title");

# SETTINGS_LANDINGPAGE_TEXT - longtext
// This is the text underneath the title of the landing page that will appear on the homepage of the blog.
define("SETTINGS_LANDINGPAGE_TEXT", "landingpage_text");

# SETTINGS_ABOUT_TEXT - longtext
// This is the about me text that will appear on the homepage of the blog.
define("SETTINGS_ABOUT_TEXT", "about_section");

# SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY - int
// This is the number of recent posts to display in the recent posts section on the homepage of the blog. Please note that setting this value to 0 will disable displaying the recent posts section.
define("SETTINGS_NUM_OF_RECENT_POSTS_TO_DISPLAY", "number_of_recent_posts_to_display");

# SETTINGS_FEATURED_IMAGE_ID_1 - int
// This is the POST_ID of the post that is set to featured position 1. Please note if this value is set to 0, then this featured post slot will be disabled. If all featured post slots are set to 0, then the featured posts section will be disabled.
define("SETTINGS_FEATURED_IMAGE_ID_1", "Featured_Image_1_Gal_ID");

# SETTINGS_FEATURED_IMAGE_ID_2 - int
// This is the POST_ID of the post that is set to featured position 2. Please note if this value is set to 0, then this featured post slot will be disabled. If all featured post slots are set to 0, then the featured posts section will be disabled.
define("SETTINGS_FEATURED_IMAGE_ID_2", "Featured_Image_2_Gal_ID");

# SETTINGS_FEATURED_IMAGE_ID_3 - int
// This is the POST_ID of the post that is set to featured position 3. Please note if this value is set to 0, then this featured post slot will be disabled. If all featured post slots are set to 0, then the featured posts section will be disabled.
define("SETTINGS_FEATURED_IMAGE_ID_3", "Featured_Image_3_Gal_ID");

# SETTINGS_FACEBOOK - varchar(1024)
// This is the link to the blog owners facebook page. If left blank, the facebook button will not display.
define("SETTINGS_FACEBOOK", "facebook_link");

# SETTINGS_EMAIL - varchar(1024)
// This is the blog owners email address. If left blank, the email button will not display.
define("SETTINGS_EMAIL", "email_address");

# SETTINGS_INSTAGRAM - varchar(1024)
// This is the link to the blog owners instagram page. If left blank, the instagram button will not display.
define("SETTINGS_INSTAGRAM", "instagram_link");

# SETTINGS_IS_FACEBOOK_ENABLED - tinyint(1) (boolean)
// This is a boolean value to determine if the facebook button is disabled.
define("SETTINGS_IS_FACEBOOK_ENABLED", "bool_is_facebook_enabled");

# SETTINGS_IS_EMAIL_ENABLED - tinyint(1) (boolean)
// This is a boolean value to determine if the email button is disabled.
define("SETTINGS_IS_EMAIL_ENABLED", "bool_is_email_enabled");

# SETTINGS_IS_INSTAGRAM_ENABLED - tinyint(1) (boolean)
// This is a boolean value to determine if the instagram button is disabled.
define("SETTINGS_IS_INSTAGRAM_ENABLED", "bool_is_instagram_enabled");

# SETTINGS_IS_ABOUT_TEXT_ENABLED - tinyint(1) (boolean)
// This is a boolean value to determine if the about me section is disabled.
define("SETTINGS_IS_ABOUT_TEXT_ENABLED", "bool_is_aboutme_enabled");

# SETTINGS_IS_LANDINGPAGE_TEXT_ENABLED - tinyint(1) (boolean)
// This is a boolean value to determine if the landing page text section is disabled.
define("SETTINGS_IS_LANDINGPAGE_TEXT_ENABLED", "bool_is_landingsubtext_enabled");


// title and description placeholders
$title = "";
$description = "";

?>
