<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
        <?php session_start(); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli-aineiston ylläpito</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>

<?php

  include 'inc/checkUserid.php';
  include "inc/start.php";
  include 'libs/models/GedDateParser.php';
  include "inc/dbconnect.php";
  
        /*
         * -- Content page starts here -->
         */

  echo '<h1>Henkilöt, joilla ei ole syntymäaikaa</h1>';
  
  $query_string = "MATCH (n:Person:" . $userid . ") "
          . "WHERE NOT (n)-[:BIRTH]->() WITH n MATCH (n)-[:HAS_NAME]->(m)"
          . "RETURN n, m ORDER BY m.last_name, m.first_name";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();
  $id = [];

  foreach ($result as $row)
  {
    $id[] = $row[0]->getProperty('id');
    $first_name[] = $row[1]->getProperty('first_name');
    $last_name[] = $row[1]->getProperty('last_name');
    $later_names[] = $row[1]->getProperty('later_names');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH_PLACE]->(p) "
            . "WHERE n.id='" . $id[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place[] = $row[0]->getProperty('name');
    }
  } 

  echo '<table  class="tulos">';
  echo '<tr><th>Id</th><th>Etunimet</th><th>Sukunimet</th>
    <th>Syntymäaika ja -paikka</th><th></th></tr>';
 
  for ($i = 0; $i < sizeof($id); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" . $id[$i] . "'>" 
            . $id[$i] . '</a></td><td>' 
            . $first_name[$i] . '</td><td> ' . $last_name[$i];
    if (isset($later_names[$i])) {
      echo ' <i>myöh.</i>&nbsp;' . $later_names[$i];
    }
    echo '</td><td>';
    if (isset($birth_date[$i])) {
      echo "<!-- $birth_date[$i] -->";
      echo GedDateParser::toDisplay($birth_date[$i]) . ' ';
    } else {
      echo '- ';
    }
    if (isset($birth_place[$i])) {
      echo $birth_place[$i];
    }
    echo "</td><td><a href='updateBirthData.php?id=" . $id[$i] 
            . "'><i>muokkaa</i></a></td></tr>";
  }
echo '</table><p>&nbsp;</p>';

/*
 *  End of content page -->
 */

include "inc/stop.php"; 
