
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
  include 'checkUserid.php';
  include "inc/start.php";
  include 'classes/DateConv.php';
  include "inc/dbconnect.php";

  /*
   * -- Content page starts here -->
   */

  $query_string = "MATCH (u:Userid) RETURN u ORDER BY u.userid";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  foreach ($result as $rows)
  {
    $user[] = $rows[0]->getProperty('userid');
  }

  echo '<table class="tulos">';
  echo '<tr><th>Käyttäjätunnukset Taapeli-kannassa</th></tr>';
  echo '<tr><th>Userid</th></tr>';
 
  for ($i=0; $i<sizeof($user); $i++) {
    echo "<tr><td><a href='showContent.php?user=" . $user[$i] . "'>" 
         . $user[$i] . "</a></td></tr>";
  }
  echo "</table><p>&nbsp;</p>";
  /*
   * --- End of content page ---
   */
  include "inc/stop.php";
