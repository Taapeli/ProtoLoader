<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli haku</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Henkil&ouml;t, jotka mahdollisesti samoja.</h1>

<?php

  require('vendor/autoload.php');

  include("openSukudb.php");

  $query_string = "MATCH (n:Person:user0498)-[r:MAY_BE_SAME]-(m:Person:user6321) WHERE r.indication1=1 AND r.indication2=1 RETURN n,m,r";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

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
    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(p) WHERE n.id='" . $id[$i] . "' RETURN p";

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
    $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place[] = $row[0]->getProperty('name');
    }
  } 

  for ($i=0; $i<sizeof($id2); $i++) {
    $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(p) WHERE n.id='" . $id2[$i] . "' RETURN p";

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
    $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id2[$i] . "' RETURN p";

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

  echo "<table  cellpadding='0' cellspacing='1' border='1'>";

  echo "<tr><th colspan='4'>Henkil&ouml;1<th colspan='4'>Henkil&ouml;2<th colspan='4'>Samat ominaisuudet</tr>";

  echo "<tr><th rowspan='2'>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
            <th rowspan='2'>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
            <th rowspan='2'>Saika<th rowspan='2'>Nimi</tr>
        <tr><th>Syntym&auml;aika<th colspan='2'>Syntym&auml;paikka
            <th>Syntym&auml;aika<th colspan='2'>Syntym&auml;paikka</tr>";
 
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
  echo "</table>";

?>

</body>
</html>
