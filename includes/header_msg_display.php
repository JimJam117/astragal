<?php  // Error and success messages
if(count($header_success_msgs) > 0){
  foreach ($header_success_msgs as $msg) {
    echo "<div class='success_msg'>$msg</div>";}
  }
  if(count($header_error_msgs) > 0){
    foreach ($header_error_msgs as $msg) {
      echo "<div class='err_msg'>$msg</div>";}
    }

    unset($header_success_msgs);
    unset($header_error_msgs);
    ?>
