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
<h1>Haku nimellä Taapeli-kannasta</h1>
<?php

  include "inc/dbconnect.php";

  if(isset($_POST['name']) || isset($_POST['wildcard'])){
    // Tiedoston käsittelyn muuttujat
    $input_name = $_POST['name'];
    $input_wildcard = $_POST['wildcard'];
    echo "<p>Poimittu nimellä = '$input_name''$input_wildcard'</p>";
    $input_wildcard = $input_wildcard . ".*";

    if ($input_name != '') {
      // Neo4j parameter {name} is used to avoid hacking injection
      $query_string = "MATCH (n:Name)<-[:HAS_NAME]-(id:Person) " .
              "WHERE n.last_name={name} RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('name' => $input_name);
    }
    else {
      // Neo4j parameter {wildcard} is used to avoid hacking injection
      $query_string = "MATCH (n:Name)<-[:HAS_NAME]-(id:Person) " .
              "WHERE n.last_name=~{wildcard} RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('wildcard' => $input_wildcard);
    }
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id[] = $rows[0]->getProperty('id');
      $first_name[] = $rows[1]->getProperty('first_name');
      $last_name[] = $rows[1]->getProperty('last_name');
      $later_names[] = $rows[1]->getProperty('later_names');
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" .
        $id[$i] . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $birth_date[] = $rows[0]->getProperty('birth_date');
      }
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" .
        $id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $birth_place[] = $rows[0]->getProperty('name');
      }
    }
  }

  echo '<table class="tulos">';
  echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
       '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  for ($i=0; $i<sizeof($first_name); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" . $id[$i] . "'>" 
         . $id[$i] . "</a></td>";
    echo "<td> " . $first_name[$i] .
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
