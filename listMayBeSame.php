<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
        <?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli haku</title>
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

  echo '<h1>Henkilöt, jotka mahdollisesti samoja</h1>';

  $query_string = "MATCH (n:Person:user0498)-[r:MAY_BE_SAME]-(m:Person:user6321)" .
          " WHERE r.indication1=1 AND r.indication2=1 RETURN n,m,r";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();
  $id = $id2 = [];

  foreach ($result as $row)
  {
    $id[] = $row[0]->getProperty('id');
    $birth_date[] = $row[0]->getProperty('birth_date');
    $id2[] = $row[1]->getProperty('id');
    $birth_date2[] = $row[1]->getProperty('birth_date');
    $indication1[] = $row[2]->getProperty('indication1');
    $indication2[] = $row[2]->getProperty('indication2');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(p)" .
            "WHERE n.id='" . $id[$i] . "' RETURN p";

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
    $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p)" .
            " WHERE n.id='" . $id[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place[] = $row[0]->getProperty('name');
    }
  } 

  for ($i=0; $i<sizeof($id2); $i++) {
    $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(p)" .
            " WHERE n.id='" . $id2[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $first_name2[] = $row[0]->getProperty('first_name');
      $last_name2[] = $row[0]->getProperty('last_name');
      $later_names2[] = $row[0]->getProperty('later_names');
    }
  }

  for ($i=0; $i<sizeof($id2); $i++) {
    $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p)" .
            " WHERE n.id='" . $id2[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place2[] = $row[0]->getProperty('name');
    }
  } 

  for ($i=0; $i<sizeof($id); $i++) {
    if ($indication1[$i] == 1)
    {
      $show_indication1[$i] = "X";
    }
    else
    {
      $show_indication1[$i] = " ";
    }
    if ($indication2[$i] == 1)
    {
      $show_indication2[$i] = "X";
    }
    else
    {
      $show_indication2[$i] = " ";
    }
  } 

  echo "<table class='tulos'>";

  echo "
    <tr><th colspan='4'>Henkilö 1</th>
        <th colspan='4'>Henkilö 2</th>
        <th colspan='2'>Samat ominaisuudet</th></tr>
    <tr><th rowspan='2'>Id</th><th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>
        <th rowspan='2'>Id</th><th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>
        <th rowspan='2'>Synt. aika</th><th rowspan='2'>Nimi</th></tr>
    <tr><th>Syntymäaika</th><th colspan='2'>Syntymäpaikka</th>
        <th>Syntymäaika</th><th colspan='2'>Syntymäpaikka</th></tr>";

  for ($i=0; $i<sizeof($id); $i++) {
    echo "<tr><td rowspan='2'><a href='compareTwoFamily.php?id=" .
         $id[$i] . "&id2=" . $id2[$i] . "'>" . $id[$i] .
         "</a></td><td>" . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $later_names[$i] .
         "</td><td rowspan='2'>" . $id2[$i] .
         "</td><td>" . $first_name2[$i] .
         "</td><td> " . $last_name2[$i] .
         "</td><td> " . $later_names2[$i] .
         "</td></tr><tr><td> " . $birth_date[$i] .
         "</td><td colspan='2'> " . $birth_place[$i] .
         "</td><td> " . $birth_date2[$i] .
         "</td><td colspan='2'> " . $birth_place2[$i] .
         "</td><td rowspan='2' align='center'>" . $show_indication1[$i] .
         "</td><td rowspan='2' align='center'>" . $show_indication2[$i] .
         "</td></tr><tr><td colspan='10'> </td></tr>";
  }
  echo "</table><p>&nbsp;</p>";

  /*
   * --- End of content page ---
   */
include "inc/stop.php";
