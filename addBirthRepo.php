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

  if ((isset($_GET['id'])) && (isset($_GET['repo']))) {
    // Tiedoston käsittelyn muuttujat
    $id = $_GET['id'];
    $repo_id = $_GET['repo'];
    $repo_source_id = "";

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $query_string = "MATCH (n:Person {id:'" . $id . 
      "'}), (r:Repo {id:'" . $repo_id . "'}) MERGE (n)-[:BIRTH_REPO]-(r)";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    if (isset($_GET['source'])) {
      $repo_source_id = $_GET['source'];

      $query_string = "MATCH (n:Person {id:'" . $id . 
        "'}), (r:Repo {id:'" . $repo_id . "'})-[:REPO_SOURCE]-(s:Repo_source {id:'" . $repo_source_id . "'}) MERGE (n)-[:BIRTH_REPO_SOURCE]-(s)";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();
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

    $query_string = "MATCH (n:Person)-[:BIRTH_REPO]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_name = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH_REPO_SOURCE]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_source = $rows[0]->getProperty('name');
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

    echo "<tr><td><th>Repo:</td><td colspan='5'> " . $repo_name .
         "</td></tr>";

    echo "<tr><td><th>Source:</td><td colspan='5'> " . $repo_source .
         "</td></tr>";
 
    echo "</table>";

  }
?>

</body>
</html>
