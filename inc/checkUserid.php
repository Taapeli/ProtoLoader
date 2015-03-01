<?php
// session_start(); must be executed first in the <header> block!
if (isset($_SESSION['taapeli']) && ($_SESSION['taapeli'] == 'on')) {
  // Ok
} else {
  $message = "Sinun tulee kirjautua Taapeliin ensin!";
  echo "<script type='text/javascript'>alert('$message');</script>";
  echo "<br /><br /><div class='goback'>
      <a href='index.php'>Etusivulle</a></div><br /><br />";
  die("<p>Tuntematon käyttäjä</p>");
}
$userid = $_SESSION['userid'];
?>

