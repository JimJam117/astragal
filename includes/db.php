<?php
  # SETTING DSN
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

  # SETTING PDO
  $pdo = new PDO($dsn, DB_USER, DB_PASS);

  #SETTING PDO DEFUALT FTECH MODE TO OBJECT
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
