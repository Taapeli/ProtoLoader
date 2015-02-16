<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli haku</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Kaikki henkil&ouml;t, joilla ei ole syntym&auml;aikaa</h1>
<h2>Lis&auml;&auml; syntym&auml;aika klikkaamalla henkil&ouml;n id:t&auml;</h2>

<?php

  include "inc/dbconnect.php";

  

  $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE NOT HAS (n.birth_date) RETURN n, m ORDER BY m.last_name, m.first_name";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  foreach ($result as $row)
  {
    $id[] = $row[0]->getProperty('id');
    $birth_date[] = $row[0]->getProperty('birth_date');
    $first_name[] = $row[1]->getProperty('first_name');
    $last_name[] = $row[1]->getProperty('last_name');
    $later_names[] = $row[1]->getProperty('later_names');
  }

  for ($i=0; $i<sizeof($id); $i++) {
    $query_string = "MATCH (n:Person)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id[$i] . "' RETURN p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_place[] = $row[0]->getProperty('name');
    }
  } 

  echo '<table  cellpadding="0" cellspacing="1" border="1">';
  echo '<tr><th>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi<th>Syntym&auml;aika
            <th>Syntym&auml;paikka</tr>';
 
  for ($i=0; $i<sizeof($id); $i++) {
    echo "<tr><td><a href='updateBirthData.php?id=" .
         $id[$i] . "'>" . $id[$i] .
         "</a></td><td>" . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $later_names[$i] .
         "</td><td> " . $birth_date[$i] .
         "</td><td> " . $birth_place[$i] .
         "</td></tr>";
  }
  echo "</table>";
?>

</body>
</html>
