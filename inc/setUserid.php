<?php
  session_start();

  if(isset($_POST['userid'])){
    $_SESSION['userid'] = $_POST['userid'];
    $_SESSION['taapeli'] = 'on';
  }

  echo "<p>K&auml;ytt&auml;j&auml;tunnus: " . $_SESSION['userid'] . " asetettu.</p>";
  echo "<div class='goback'><a href='/index.php'>Paluu</a></div>";

?>

