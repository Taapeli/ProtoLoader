<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
        <?php session_start(); ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli haku</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
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

  echo '<h1>Henkilöt, joilla on tarkistettavaa tietoa.</h1>';
  
  $query_string = "MATCH (n:Person:" . $userid . 
    ")-[:HAS_NAME]-(m), (n)-[:TODO]->(t) RETURN n,m,t ORDER BY m.last_name, m.first_name";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  foreach ($result as $row)
  {
    $id[] = $row[0]->getProperty('id');
    $first_name[] = $row[1]->getProperty('first_name');
    $last_name[] = $row[1]->getProperty('last_name');
    $later_names[] = $row[1]->getProperty('later_names');
    $todo_description[] = $row[2]->getProperty('description');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b) WHERE n.id='" . $id[$i] . "' RETURN b";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_date[] = $row[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . 
      "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place[] = $row[0]->getProperty('name');
    }
  } 

  echo '<table  class="tulos">';
  echo '<tr><th>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi<th>Syntym&auml;aika
            <th>Syntym&auml;paikka</tr>';
 
  for ($i=0; $i<sizeof($id); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" .
         $id[$i] . "'>" . $id[$i] .
         "</a></td><td>" . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $later_names[$i] .
         "</td><td> " . $birth_date[$i] .
         "</td><td> " . $birth_place[$i] .
         "</td></tr>";
    echo "<tr><th>Huomautus:<td colspan='5'>" . $todo_description[$i] .
         "</td></tr>";
  }
  echo "</table>";
?>

<br><br><br>
<h1>Kaikki henkil&ouml;t, joilla on avioliittotiedoissa tarkistettavaa tietoa.</h1>

<?php

  $query_string = "MATCH (n:Person)-[:HAS_NAME]-(m), (n)-[:MARRIED]-()-[:TODO]->(t) RETURN n,m,t ORDER BY m.last_name, m.first_name";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  foreach ($result as $row)
  {
    $marr_id[] = $row[0]->getProperty('id');
    $marr_first_name[] = $row[1]->getProperty('first_name');
    $marr_last_name[] = $row[1]->getProperty('last_name');
    $marr_later_names[] = $row[1]->getProperty('later_names');
    $marr_todo_description[] = $row[2]->getProperty('description');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $id[$i] . "' RETURN b";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $marr_birth_date[] = $row[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $marr_birth_place[] = $row[0]->getProperty('name');
    }
  } 

  echo '<table  class="tulos">';
  echo '<tr><th>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi<th>Syntym&auml;aika
            <th>Syntym&auml;paikka</tr>';
 
  for ($i=0; $i<sizeof($id); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" .
         $id[$i] . "'>" . $marr_id[$i] .
         "</a></td><td>" . $marr_first_name[$i] .
         "</td><td> " . $marr_last_name[$i] .
         "</td><td> " . $marr_later_names[$i] .
         "</td><td> " . $marr_birth_date[$i] .
         "</td><td> " . $marr_birth_place[$i] .
         "</td></tr>";
    echo "<tr><th>Huomautus:<td colspan='5'>" . $marr_todo_description[$i] .
         "</td></tr>";
  }
  echo "</table>";

  /*
   *  -- End of content page -->
   */

include "inc/stop.php";
