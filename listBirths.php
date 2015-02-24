<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli haku</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    
<h1>Haku syntymäajalla Taapeli-kannasta</h1>

<?php
  include 'checkUserid.php';
  include "inc/start.php";
  include 'classes/DateConv.php';
  include "inc/dbconnect.php";

  if(isset($_POST['birth'])){
    // Input variables
    $input_birth = htmlentities($_POST['birth']);
    echo "<h1>Haku syntymäajalla <i>$input_birth</i></h1>";

    // Neo4j parameter {birth} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b:Birth) WHERE b.birth_date={birth} RETURN n, b";

    $query_array = array('birth' => $input_birth);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();
    $id = [];

    foreach ($result as $row)
    {
      $id[] = $row[0]->getProperty('id');
      $birth_date[] = $row[1]->getProperty('birth_date');
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:HAS_NAME]->(m) WHERE n.id='" . $id[$i] . "'  RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $row)
      {
        $first_name[] = $row[0]->getProperty('first_name');
        $last_name[] = $row[0]->getProperty('last_name');
        $later_names[] = $row[0]->getProperty('later_names');
      }
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:BIRTH]->(b:Birth)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . "' RETURN p";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $row)
      {
        $birth_place[] = $row[0]->getProperty('name');
      }
    } 
  }

  echo '<table  class="tulos">';
  echo '<tr><th>Id<th>Etunimet<th>Sukunimi<th>Myöh. sukunimi<th>Syntymäaika
            <th>Syntymäpaikka</tr>';
 
  for ($i=0; $i<sizeof($id); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" .
         $id[$i] . "'>" . $id[$i] .
         "</a></td><td>" . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $later_names[$i] .
         "</td><td> " . $birth_date[$i] .
         "</td><td> " . $birth_place[$i] .
         "</td></tr>";
  }
  echo "</table>";

  include "inc/stop.php"; 
