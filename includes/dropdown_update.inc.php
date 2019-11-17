<?php

// var = variable to update, $db_col = the column in the table to update, $pdo_obj = the pdo object
function dropdown_update($var, $db_col, $pdo_obj) {
  // grab the settings table
  $pref = $pdo_obj->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = '1'");
  $pref = $pref->fetch();

  // if var is empty
  if(empty($var)) { return false; }

  else if ($pref->{$db_col} != $var) {
    // change the value in the database
    $stmt = $pdo_obj->prepare("UPDATE " . SETTINGS_TABLE . " SET " . $db_col . " = :var WHERE " . SETTINGS_PROFILE_ID . " = '1'");
    $stmt->bindParam(':var', $var, PDO::PARAM_INT);
    $stmt->execute();

    return true;
  }
}
