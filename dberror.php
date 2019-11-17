<?php
//link to database
include_once ("includes/config.php");
include_once ("includes/db.php");
error_reporting(0);
 ?>


 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Ooopsie!</title>
     <style media="screen">
       body{
         text-align: center;
         font-size: large;
         font-family: monospace;
         color: red;
          background-image: linear-gradient(to right, rgb(120, 23, 23),black,rgb(120, 23, 23));
       }

     </style>
   </head>

   <body>

       <img src="dberror.png" alt="">
       <h1>Error with the database, connection is down</h1>
       <p>Check the database is set up properly</p>
       <?php if(isset($_GET['dberror'])){ echo "<p>Database Error Message: $_GET[dberror]</p>";} ?>
       <a href="home.php">Home</a>


   </body>

 </html>
