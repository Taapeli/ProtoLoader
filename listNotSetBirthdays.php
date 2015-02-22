<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<?php include 'checkUserid.php'; ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli-aineiston ylläpito</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div  class="goback">
  <a href="#" onclick="history.go(-1)">Paluu</a></div>
<h1>Henkilöt, joilla ei ole syntymäaikaa</h1>
<h2>Lisää syntymäaika osoittamalla henkilön id:tä</h2>

<?php

  include 'classes/DateConv.php';
  include "inc/dbconnect.php";

  $query_string = "MATCH (n:Person:" . $userid . ")-[:HAS_NAME]->(m) "
          . "WHERE NOT HAS (n.birth_date) "
          . "RETURN n, m ORDER BY m.last_name, m.first_name";

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
  echo '<tr><th>Id</th><th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>
    <th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  for ($i = 0; $i < sizeof($id); $i++) {
    echo "<tr><td><a href='updateBirthData.php?id=" . $id[$i] . "'>" 
            . $id[$i] . '</a></td><td>' 
            . $first_name[$i] . '</td><td> ' 
            . $last_name[$i] . '</td><td> ';
    if (isset($later_names[$i])) {
      echo $later_names[$i];
    }
    echo '</td><td> ';
    if (isset($birth_date[$i])) {
      echo DateConv::toDisplay($birth_date[$i]);
    }
    echo '</td><td> ';
    if (isset($birth_place[$i])) {
      echo $birth_place[$i];
    }
    echo "</td></tr>\n";
  }
echo '</table>';
?>

</body>
</html>
