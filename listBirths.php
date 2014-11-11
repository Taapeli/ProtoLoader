<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli haku</title>
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
<h1>Haku syntymäajalla Taapeli-kannasta</h1>

<?php

  require('vendor/autoload.php');

  if(isset($_POST['birth'])){
    // Tiedoston käsittelyn muuttujat
    $birth = $_POST['birth'];
    echo "<p>Poiminta syntymäaika = '$birth'</p>";

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $query_string = "MATCH (n:Person) WHERE n.birth_date='" . $birth . "' RETURN n";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $id[] = $row[0]->getProperty('id');
      $birth_date[] = $row[0]->getProperty('birth_date');
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . $id[$i] . "'  RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $row)
      {
        $first_name[] = $row[0]->getProperty('first_name');
        $last_name[] = $row[0]->getProperty('last_name');
        $later_names[] = $row[0]->getProperty('later_name(s)');
      }
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
  }

  echo '<table  cellpadding="0" cellspacing="1" border="1">';
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
/*
         "</a></td><td>" . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $later_names[$i] .
         "</td><td> " . $birth_date[$i] .
         "</td><td> " . $birth_place[$i] . */
  }
  echo "</table>";
?>

</body>
</html>
