

<?php



// checkboxes //

// facebook
$is_facebook_enabled_string = "";
if($is_facebook_enabled == true) {$is_facebook_enabled_string = "1";}
else { $is_facebook_enabled_string = "0"; }
// if db is not the same as string_var
if($p2['bool_is_facebook_enabled'] != $is_facebook_enabled_string){
  // if link is deactivated
  if($is_facebook_enabled == false) {
    $header_msgs[] = "success_msg_facebook_bool=Successfully deactivated facebook link!";

    $db->query("UPDATE pref SET bool_is_facebook_enabled = '0' WHERE user_id = '1'");
  }
  // if link is activated
  else if ($is_facebook_enabled == true) {
    $header_msgs[] = "success_msg_facebook_bool=Successfully activated facebook link!";

    $db->query("UPDATE pref SET bool_is_facebook_enabled = '1' WHERE user_id = '1'");
  }
}

// email
$is_email_enabled_string = "";
if($is_email_enabled == true) {$is_email_enabled_string = "1";}
else { $is_email_enabled_string = "0"; }
// if link is deactivated
if($p2['bool_is_email_enabled'] != $is_email_enabled_string){
  if($is_email_enabled == false) {
    $header_msgs[] = "success_msg_email_bool=Successfully deactivated email link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $db->query("UPDATE pref SET bool_is_email_enabled = '0' WHERE user_id = '1'");
  }
  // if link is activated
  else if ($is_email_enabled == true) {
    $header_msgs[] = "success_msg_email_bool=Successfully activated email link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $db->query("UPDATE pref SET bool_is_email_enabled = '1' WHERE user_id = '1'");
  }
}

// instagram
$is_instagram_enabled_string = "";
if($is_instagram_enabled == true) {$is_instagram_enabled_string = "1";}
else { $is_instagram_enabled_string = "0"; }
// if link is deactivated
if($p2['bool_is_instagram_enabled'] != $is_instagram_enabled_string){
  if($is_instagram_enabled == false) {
    $header_msgs[] = "success_msg_instagram_bool=Successfully deactivated instagram link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $db->query("UPDATE pref SET bool_is_instagram_enabled = '0' WHERE user_id = '1'");
  }
  // if link is activated
  else if ($is_instagram_enabled == true) {
    $header_msgs[] = "success_msg_instagram_bool=Successfully activated instagram link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $db->query("UPDATE pref SET bool_is_instagram_enabled = '1' WHERE user_id = '1'");
  }
}























// links
if($is_facebook_enabled) {
  if(empty($facebook_link)) {
    $header_msgs[] = "err_msg12=Error, no facebook link!";
  }
  else if ($p2['facebook_link'] != $facebook_link) {
    $header_msgs[] = "success_msg12=Successfully updated facebook link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $query = "UPDATE pref SET facebook_link = '$facebook_link'
    WHERE user_id = '1'";
    $db->query($query);
  }
}

if($is_email_enabled) {
  if(empty($email_address)) {
    $header_msgs[] = "err_msg13=Error, no email address!";
  }
  else if ($p2['email_address'] != $email_address) {
    $header_msgs[] = "success_msg13=Successfully updated email address!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $query = "UPDATE pref SET email_address = '$email_address'
    WHERE user_id = '1'";
    $db->query($query);
  }
}

if($is_instagram_enabled) {
  if(empty($instagram_link)) {
    $header_msgs[] = "err_msg14=Error, no instagram link!";
  }
  else if ($p2['instagram_link'] != $instagram_link) {
    $header_msgs[] = "success_msg14=Successfully updated instagram link!";
    $id = mysqli_real_escape_string($db, $_POST['id']);
    $query = "UPDATE pref SET instagram_link = '$instagram_link'
    WHERE user_id = '1'";
    $db->query($query);
  }
}
}


?>





















<tr>
  <td>
    <?php
      if($pref['bool_is_facebook_enabled'] == true) { $is_facebook_enabled = true; ?>
        echo "<label class='checkbox_container'> <b class='sub-header'><i class='fab fa-facebook-square'></i> Facebook Link</b>";
        <input type="checkbox" name="bool_is_facebook_enabled" checked onclick="var input = document.getElementById('fb_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
        </label>
      <?php }
      else { ?>
        $is_facebook_enabled = false;
        <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-facebook-square"></i> Facebook Link</b>
        <input type="checkbox" name="bool_is_facebook_enabled" onclick="var input = document.getElementById('fb_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
        </label>
      <?php } ?>
  </td>
  <td></td>
  <td>
    <?php // if checked, display enabled input
    if($is_facebook_enabled == true)  { ?>
      <input id="fb_link" class="form-control" type="text" name="facebook_link" minlength="15" maxlength="1000" value="<?php echo $pref['facebook_link']?>"/>
    <?php } else { ?>
      <input id="fb_link" disabled="disabled" class="form-control" type="text" name="facebook_link" minlength="15" maxlength="1000" value="<?php echo $pref['facebook_link']?>"/>
    <?php } ?>

  </td>
</tr>

<tr class="form-group">
  <td>
    <?php if($pref['bool_is_email_enabled'] == true) { $is_email_enabled = true; ?>
      <label class="checkbox_container"><b class="sub-header"><i class="fas fa-envelope-square"></i> Email Address</b>
        <input type="checkbox" checked name="bool_is_email_enabled" onclick="var input = document.getElementById('email'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
      </label>
    <?php } else { $is_email_enabled = false; ?>
      <label class="checkbox_container"><b class="sub-header"><i class="fas fa-envelope-square"></i> Email Address</b>
        <input type="checkbox" name="bool_is_email_enabled" onclick="var input = document.getElementById('email'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
      </label>
    <?php } ?>


  </td>
  <td></td>
  <td>
    <?php // if checked, display enabled input
    if($is_email_enabled == true)  { ?>
      <input id="email" class="form-control" type="text" name="email_address" minlength="3" maxlength="70" value="<?php echo $pref['email_address']?>"/>
    <?php } else { ?>
      <input id="email" disabled="disabled" class="form-control" type="text" name="email_address" minlength="3" maxlength="70" value="<?php echo $pref['email_address']?>"/>
    <?php } ?>
  </td>
</tr>

<tr class="form-group">
  <td>
    <?php if($pref['bool_is_instagram_enabled'] == true) { $is_instagram_enabled = true; ?>
      <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-instagram"></i> Instagram Link</b>
        <input type="checkbox" checked name="bool_is_instagram_enabled" onclick="var input = document.getElementById('instagram_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
      </label>
    <?php } else { $is_instagram_enabled = false; ?>
      <label class="checkbox_container"> <b class="sub-header"><i class="fab fa-instagram"></i> Instagram Link</b>
        <input type="checkbox" name="bool_is_instagram_enabled" onclick="var input = document.getElementById('instagram_link'); if(this.checked){ input.disabled = false; input.focus();}else{input.disabled=true;}" />
        <span class="checkmark"></span>
      </label>
    <?php } ?>
  </td>
  <td></td>
  <td>
    <?php // if checked, display enabled input
    if($is_instagram_enabled == true)  { ?>
      <input id="instagram_link" class="form-control" type="text" name="instagram_link" minlength="15" maxlength="1000" value="<?php echo $pref['instagram_link']?>"/></td>
    <?php } else { ?>
      <input id="instagram_link" disabled="disabled" class="form-control" type="text" name="instagram_link" minlength="15" maxlength="1000" value="<?php echo $pref['instagram_link']?>"/></td>
    <?php } ?>
  </tr>
