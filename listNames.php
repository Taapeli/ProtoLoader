<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kantaan</title>
<style>
b { color:red }
.form { background-color: #dde; margin-left: auto; margin-right: auto; }
th,td { padding: 5px; }
</style>
</head>

<body>
<h1>Taapeli testiluku</h1>
<p>Luetaan neo4j-tietokannasta.</p>
<?php

  require('vendor/autoload.php');

/*-------------------------- Tiedoston luku ----------------------------*/
/*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if(isset($_POST['name'])){
    // Tiedoston käsittelyn muuttujat
    $name = $_POST['name'];

    require('vendor/autoload.php');

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $query_string = "MATCH (n:Name)-[:HAS_NAME]-(id)-[:BIRTH]-(m) WHERE n.last_name='" . $name . "' RETURN n, m, id ORDER BY n.first_name";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name[] = $rows[0]->getProperty('first_name');
      $last_name[] = $rows[0]->getProperty('last_name');
      $birth_date[] = $rows[1]->getProperty('birth_date');
      $birth_place[] = $rows[1]->getProperty('birth_place');
      $id[] = $rows[2]->getProperty('id');
    }
  }

  echo '<table  cellpadding="0" cellspacing="1" border="1">';
  echo '<tr><th>id<th>Etunimet<th>Sukunimi<th>Syntym&auml;aika<th>Syntym&auml;paikka</tr>';
 
  for ($i=0; $i<sizeof($first_name); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" .
         $id[$i] . "'>" . $id[$i] .
         "</a></td><td> " . $first_name[$i] .
         "</td><td> " . $last_name[$i] .
         "</td><td> " . $birth_date[$i] .
         "</td><td> " . $birth_place[$i] .
         "</td></tr>";
  }
  echo "</table>";
?>

</body>
</html>
