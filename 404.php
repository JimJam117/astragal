<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Oopsie</title>
</head>
<body>
  <img src="404.png" alt="">
  <h1>Error, page not found</h1>
  <?php if(isset($_GET['err_msg'])){
    echo "<h3>" . $_GET['err_msg'] . "</h3>";
  } ?>
  <h4><a href="home.php">Back to home page</a></h4>
</body>
</html>
