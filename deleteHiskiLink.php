<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston yll&auml;pito kannassa</title>
<style>
b { color:red }
.form { background-color: #dde; margin-left: auto; margin-right: auto; }
th,td { padding: 5px; }
</style>
</head>
<body>
<div style="display: block; width: 100px; position: fixed;
    top: 1em; right: 1em; color: #FFF;
    background-color: #ddd;
    text-align: center; padding: 4px; text-decoration: none;">
  <a href="index.php">Paluu</a></div>
<h1>Taapeli testiyll&auml;pito</h1>
<p>Tiedot poistettu neo4j-tietokannasta.</p>

<?php

  require('vendor/autoload.php');

/*-------------------------- Tiedoston luku ----------------------------*/
/*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if(isset($_POST['id']) && isset($_POST['hiski'])) {
    // Tiedoston käsittelyn muuttujat
    $id = $_POST['id'];
    $hiski = $_POST['hiski'];

    // echo "id: '$id' hiski: '$hiski'";

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $sourceLabel = $sukudb->makeLabel('Source');

    $query_string = "MATCH (n:Person {id:'" . $id . 
      "'})-[r:HISKI_LINK]->(m:Source {hiski_link:'" . $hiski . 
      "'}) DELETE r";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    $query_string = "MATCH (n:Person) WHERE n.id='" . $id . "' RETURN n";
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

    $query_string = "MATCH (n:Person)-[:HISKI_LINK]-(m:Source) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $hiski_link[] = $rows[0]->getProperty('hiski_link');
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

    if (sizeof($hiski_link) > 0) {
      for ($i=0; $i<sizeof($hiski_link); $i++) {
        echo "<tr><td></td><td>Hiski-linkki:<td><a href='http://hiski.genealogia.fi/hiski?fi+t"  . $hiski_link[$i] . "' target='_blank'>" . $hiski_link[$i] .
           "</a></td><td></td><td></td><td></td></tr>";
      }
    }
    else {
      echo "<tr><td></td><td>Hiski-linkki:<td>-</td><td></td><td></td><td></td></tr>";
    }
 
    echo "</table>";

  }
?>

</body>
</html>
