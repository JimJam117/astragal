<?php // footer ?>

<div class="footer" >

  <hr>
  <br>
  <?php

  $pref = $pdo->query("SELECT * FROM " . SETTINGS_TABLE . " WHERE " . SETTINGS_PROFILE_ID . " = 1");
  $pref = $pref->fetch();

  $EmailIsDisplaying = true;
  if(empty($pref->{SETTINGS_EMAIL}) || $pref->{SETTINGS_IS_EMAIL_ENABLED} == "0"){ $EmailIsDisplaying = false; }
  else{ $EmailIsDisplaying = true; }


  if($EmailIsDisplaying) {
    echo $pref->{SETTINGS_EMAIL};
    echo " <br><br> <a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
  }
  else {
    echo "<a href='$APPAUTHORADDRESS'> $APPNAME $APPVERSION by $APPAUTHOR </a>";
  }
  ?>

</div>

</div> <!--MAIN CONTENT END-->

</body>
</html>
