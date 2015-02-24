<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - puuttuvat Hiski-linkit</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

<body>
<?php
include 'checkUserid.php';
    include "inc/start.php";
    include 'classes/DateConv.php';
    include "inc/dbconnect.php";

    echo '<h1>Henkil&ouml;t, joilla ei ole Hiski-linkki&auml;</h1>
    <p>Lis&auml;&auml; Hiski-linkki klikkaamalla henkil&ouml;n id:t&auml;<p>';

    $query_string = "MATCH (n:Person:" . $userid . ")-[:HAS_NAME]-(m) 
    OPTIONAL MATCH (n)-[r:HISKI_LINK]->() WITH n,r,m 
    WHERE r IS NULL RETURN n,m ORDER BY m.last_name, m.first_name";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  foreach ($result as $row)
  {
    $id[] = $row[0]->getProperty('id');
    $first_name[] = $row[1]->getProperty('first_name');
    $last_name[] = $row[1]->getProperty('last_name');
    $later_names[] = $row[1]->getProperty('later_names');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b) WHERE n.id='" . $id[$i] . "' RETURN b";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_date[] = $row[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . 
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
         "</td><td> " . DateConv::toDisplay($birth_date[$i]) .
         "</td><td> " . $birth_place[$i] .
         "</td></tr>";
  }
  echo "</table>";
  
  /*
   * --- End of content page ---
   */
include "inc/stop.php";
