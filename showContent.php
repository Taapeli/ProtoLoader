<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - tietojen poisto</title>
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

  echo '<h1>Poista käyttäjän lataamat tiedot</h1>';

  if (!isset($_GET['user'])) {
    echo '<p>Ei valittua henkilöä</p></body></html>';
    die;
  }

  // Tiedoston käsittelyn muuttujat
  $user = $_GET['user'];

  $query_string = "MATCH (n:Person:" . $user . ") RETURN COUNT(n)";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();


  foreach ($result as $rows)
  {
      $counter_p = $rows[0];
  }

  $query_string = "MATCH (n:Person:" . $user . ")-[r]-() RETURN TYPE(r), COUNT(*)";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();


  foreach ($result as $rows)
  {
      $type[] = $rows[0];
      $counter[] = $rows[1];
  }

  echo '<table class="tulos">';
  echo '<tr><th>Käyttäjä</th><th>' . $user . '</th></tr>';
  echo '<tr><th>Henkilöitä</th><th>' . $counter_p . '</th></tr>';
  echo '<tr><th>Relaatio</th><th>Lukumäärä</th></tr>';
 
  for ($i=0; $i<sizeof($type); $i++) {
    echo "<tr><td>" . $type[$i] . "</td><td>" . $counter[$i] . "</td></tr>";
  }
  echo "</table><p>&nbsp;</p>";

?>
  <h2>Toiminnot</h2>
  <ul>
    <li>
      <form action="deleteUser.php" method="get" enctype="multipart/form-data">
        <p>Poista valitun henkilön tiedot
            <input type="hidden" name="user" value="<?php echo $user; ?>" />
            <input type="submit" value="Poista tiedot"/></p>
      </form>
    </li>
  </ul>

<?php

  /*
   *  -- End of content page -->
   */

include "inc/stop.php";
