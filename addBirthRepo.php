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
    $input_id = $_GET['id'];
    $input_repo_id = $_GET['repo'];
    $input_repo_source_id = "";
    $input_page = "";

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    if (isset($_GET['page'])) {
      $input_page = $_GET['page'];
    }

    if (isset($_GET['source'])) {
      $input_repo_source_id = $_GET['source'];

      // Neo4j parameters {id}, {repo_id}, {repo_source_id} and
      // {page} are used to avoid hacking injection
      $query_string = "MATCH (n:Person {id:{id}}), (r:Repo {id:{repo_id}})-[:REPO_SOURCE]-(s:Source {id:{repo_source_id}}) MERGE (n)-[p:BIRTH_SOURCE]-(s) SET p.page={page}";

      $query_array = array('id' => $input_id, 'repo_source_id' => $input_repo_source_id, 'repo_id' => $input_repo_id, 'page' => $input_page);

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
      $result = $query->getResultSet();
    }
    else {
      // Neo4j parameters {id}, {repo_id} and {page} are used
      // to avoid hacking injection
      $query_string = "MATCH (n:Person {id:{id}}), (r:Repo {id:{repo_id}}) MERGE (n)-[p:BIRTH_REPO]-(r) SET p.page={page}";

      $query_array = array('id' => $input_id, 'repo_id' => $input_repo_id, 'page' => $input_page);

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
      $result = $query->getResultSet();
    }

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person) WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
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

    $query_string = "MATCH (n:Person)-[p:BIRTH_SOURCE]-(s:Source)-[:REPO_SOURCE]-(r:Repo) WHERE n.id='" . $id . "' RETURN r,s,p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_name[] = $rows[0]->getProperty('name');
      $repo_source[] = $rows[1]->getProperty('title');
      $repo_page[] = $rows[2]->getProperty('page');
    }

    $query_string = "MATCH (n:Person)-[p:BIRTH_REPO]-(r:Repo) WHERE n.id='" . $id . "' RETURN r, p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_name_only[] = $rows[0]->getProperty('name');
      $repo_page_only[] = $rows[1]->getProperty('page');
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

    echo "<tr><th> <th colspan='4'>Repo/Source<th>Sivu</tr>";

    for ($i=0; $i<sizeof($repo_source); $i++) {
      echo "<tr><td rowspan='2'></td><td colspan='5'> " . $repo_name[$i] .
         "</td></tr>";

      echo "<tr><td colspan='4'> " . $repo_source[$i] .
         "</td><td>" . $repo_page[$i] . "</td></tr>";
    }

    for ($i=0; $i<sizeof($repo_name_only); $i++) {
      echo "<tr><td></td><td colspan='4'> " . $repo_name_only[$i] .
         "</td><td>" . $repo_page_only[$i] . "</td></tr>";
    }
 
    echo "</table>";

  }
?>

</body>
</html>
