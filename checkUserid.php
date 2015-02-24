<?php
  // session_start(); must be executed first in the <header> block!
  if ($_SESSION['taapeli'] != 'on') {
    $message = "Sinun tulee kirjautua Taapeliin ensin!";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo "<br><br><div class='goback'>
      <a href='index.php'>Kirjaudu</a></div><br><br>";
    die("Tuntematon k&auml;ytt&auml;j&auml;");
  }
  $userid = $_SESSION['userid'];
?>

