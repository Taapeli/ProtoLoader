<?php
  session_start();

  if(isset($_POST['userid'])){
    $_SESSION['userid'] = $_POST['userid'];
  }

  echo "K&auml;ytt&auml;j&auml;tunnus: " . $_SESSION['userid'] . " asetettu.<br><br>";
  echo "<div class='goback'><a href='index.php'>Paluu</a></div>";

?>

