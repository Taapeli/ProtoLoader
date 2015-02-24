<?php

session_start();

if (isset($_POST['userid'])) {
  $uid = strip_tags(trim(($_POST['userid'])));
  if (strlen($uid) > 5) {
    $_SESSION['userid'] = $uid;
    $_SESSION['taapeli'] = 'on';
    echo $_SESSION['userid'] . ", kirjaudu <a href='logout.php'>ulos</a>";
  } else {
            echo "<p style='color=red'>Käyttäjätunnus on virheellinen<br />";
            echo "<a href='alku.php'>Paluu</a><p>";
  }
}

