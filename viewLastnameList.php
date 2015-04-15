
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelista haku</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
<?php
  require_once 'listNamesClass.php';

  echo '<table class="tulos">';
  echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
       '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  for ($i=0; $i<sizeof($individ); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" . $individ[$i]->getId() . "'>" 
         . $individ[$i]->getId() . "</a></td>";
    echo "<td> " . $individ[$i]->getFirstname() .
         "</td><td> " . $individ[$i]->getLastname() .
         "</td><td> " . $individ[$i]->getLaternames() . 
         "</td><td> " . $individ[$i]->getBirthdate() .
         "</td><td> " . $individ[$i]->getBirthplace() .
         "</td></tr>";
  }
  echo "</table><p>&nbsp;</p>";
  /*
   * --- End of content page ---
   */
include "inc/stop.php";
