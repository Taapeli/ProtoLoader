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
	
    require('vendor/autoload.php');

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $query_string = "MATCH (n:Birth)<-[:BIRTH]-(id:Person)-[:HAS_NAME]-(m) WHERE n.birth_date='" . $birth . "' MATCH (n)-[:PLACE]->(p) RETURN DISTINCT n, m, p, id ORDER BY m.last_name, m.first_name";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $row)
    {
      $birth_date[] = $row[0]->getProperty('birth_date');
      $first_name[] = $row[1]->getProperty('first_name');
      $last_name[] = $row[1]->getProperty('last_name');
      $later_names[] = $row[1]->getProperty('later_name(s)');
      $birth_place[] = $row[2]->getProperty('name');
      $id[] = $row[3]->getProperty('id');
    }
  }

  echo '<table  cellpadding="0" cellspacing="1" border="1">';
  echo '<tr><th>Id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi<th>Syntym&auml;aika
            <th>Syntym&auml;paikka</tr>';
 
  for ($i=0; $i<sizeof($first_name); $i++) {
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
?>

</body>
</html>
