<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston yll&auml;pito kannassa</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Taapeli testiyll&auml;pito</h1>
<p>Tiedot muutettu neo4j-tietokantaan.</p>

<?php

  require('vendor/autoload.php');

/*-------------------------- Tiedoston luku ----------------------------*/
/*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if ((isset($_POST['id'])) && (isset($_POST['prevPlace'])) && ((isset($_POST['birth'])) || (isset($_POST['prevPlace'])))) {
    // Tiedoston käsittelyn muuttujat
    $id = $_POST['id'];
    $prev_place = $_POST['prevPlace'];
    $birth = $_POST['birth'];
    $place = $_POST['place'];

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    if ($birth) {
      $query_string = "MATCH (n:Person) WHERE n.id='" . $id . 
        "' SET n.birth_date='" . $birth . "' RETURN n";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();
    }

    if ($place && ($prev_place <> $place)) {
      
      $query_string = "MATCH (p:Place {name:'" . $place . 
        "'}) RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $birth_place = $rows[0]->getProperty('name');
      }

      if (!$birth_place) {
?>
  <script type="text/javascript">
    alert("Syntymäpaikkaa ei muutettu. Tarkista kirjoitusasu!");
    history.back();
  </script>
<?php
      }
      else {
        $query_string = "MATCH (n:Person)-[r:BIRTH_PLACE]-() WHERE n.id='" . $id . 
          "' DELETE r";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
        $result = $query->getResultSet();

        $query_string = "MATCH (n:Person {id:'" . $id . 
          "'}), (p:Place {name:'" . $place . 
          "'}) MERGE (n)-[:BIRTH_PLACE]->(p)";

        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();
      }
    }

    $query_string = "MATCH (n:Person) WHERE n.id='" . $id . 
      "' RETURN n";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_date = $rows[0]->getProperty('birth_date');
      $death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
      $later_names = $rows[0]->getProperty('later_name(s)');
    }

    echo '<table  cellpadding="0" cellspacing="1" border="1">';
    echo '<tr><th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi<th>Syntym&auml;aika<th>Syntym&auml;paikka</tr>';

    echo "<tr><td>" . $id .
         "</td><td> " . $first_name .
         "</td><td> " . $last_name .
         "</td><td> " . $later_names .
         "</td><td> " . $birth_date .
         "</td><td> " . $birth_place .
         "</td></tr>";
 
    echo "</table>";

  }
?>

</body>
</html>
