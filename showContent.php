<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - Käyttäjän luomat tiedot</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
<?php
  include 'inc/checkUserid.php';
  include "inc/start.php";
  include "inc/dbconnect.php";
  
  /*
   * -- Content page starts here -->
   */

  if (!isset($_GET['user'])) {
    echo '<p>Ei valittua henkilöä</p></body></html>';
    die;
  }

  // Tiedoston käsittelyn muuttujat
  $user = htmlentities($_GET['user']);

  $query_string = "MATCH (n:Person:" . $user . ") RETURN COUNT(n)";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();


  foreach ($result as $rows)
  {
      $counter_p = $rows[0];
  }

  $query_string = "MATCH (n:Person:" . $user . ")-[r]-() RETURN TYPE(r), COUNT(*) ORDER BY TYPE(r)";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();


  foreach ($result as $rows)
  {
      $type[] = $rows[0];
      $counter[] = $rows[1];
  }

  echo '<h1>Käyttäjän ' .  $user . ' lataamat tiedot</h1>';

  echo '<h3>Henkilöitä kannassa: ' . $counter_p . '</h3>';

  echo '<table class="tulos">';
  echo '<tr><th>Relaation nimi</th><th>Lukumäärä</th></tr>';
 
  for ($i=0; $i<sizeof($type); $i++) {
    echo "<tr><td>" . $type[$i] . "</td><td align='right'>" . $counter[$i] . "</td></tr>";
  }
  echo "</table><h2>Toiminnot</h2><ul><li>";

      if ($_SESSION['userid'] == $user) {
?>
  
      <form action="deleteUser.php" method="get" enctype="multipart/form-data">
          Poista käyttäjän <i><?php $user ?></i> tallettamat tiedot
            <input type="hidden" name="user" value="<?php echo $user; ?>" />
            <input type="submit" value="Poista tiedot"/>
      </form>
    

<?php
      } else {
        echo "Voit poistaa vain kirjautuneen käyttäjän itse tallettamat tiedot";
      };
      echo "</li></ul><p>&nbsp;</p>";
  /*
   *  -- End of content page -->
   */

include "inc/stop.php";
