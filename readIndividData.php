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

  if(isset($_GET['id'])){
    // Tiedoston käsittelyn muuttujat
    $id = $_GET['id'];

    require('vendor/autoload.php');

    $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

    $query_string = "MATCH (n:Person)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_date = $rows[0]->getProperty('birth_date');
      $birth_place = $rows[0]->getProperty('birth_place');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]-(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_date = $rows[0]->getProperty('death_date');
      $death_place = $rows[0]->getProperty('death_place');
    }

    $query_string = "MATCH (n:Person)-[:FATHER]->(id)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m, id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_first_name = $rows[0]->getProperty('first_name');
      $father_last_name = $rows[0]->getProperty('last_name');
      $father_id = $rows[1]->getProperty('id');
    }

    for ($i=0; $i<sizeof($father_id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]-(m) WHERE n.id='" . $father_id . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $father_birth_date = $rows[0]->getProperty('birth_date');
        $father_birth_place = $rows[0]->getProperty('birth_place');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]-(m) WHERE n.id='" . $father_id . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $father_death_date = $rows[0]->getProperty('death_date');
        $father_death_place = $rows[0]->getProperty('death_place');
      }
    }

    $query_string = "MATCH (n:Person)-[:MOTHER]->(id)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m, id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_first_name = $rows[0]->getProperty('first_name');
      $mother_last_name = $rows[0]->getProperty('last_name');
      $mother_id = $rows[1]->getProperty('id');
    }

    for ($i=0; $i<sizeof($father_id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]-(m) WHERE n.id='" . $mother_id . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $mother_birth_date = $rows[0]->getProperty('birth_date');
        $mother_birth_place = $rows[0]->getProperty('birth_place');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]-(m) WHERE n.id='" . $mother_id . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $mother_death_date = $rows[0]->getProperty('death_date');
        $mother_death_place = $rows[0]->getProperty('death_place');
      }
    }

    $query_string = "MATCH (n:Person)-[:MARRIED]-(id)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m, id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $spouse_first_name[] = $rows[0]->getProperty('first_name');
      $spouse_last_name[] = $rows[0]->getProperty('last_name');
      $spouse_id[] = $rows[1]->getProperty('id');
    }

    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]-(m) WHERE n.id='" . $spouse_id[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_date[$i] = $rows[0]->getProperty('birth_date');
        $spouse_birth_place[$i] = $rows[0]->getProperty('birth_place');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]-(m) WHERE n.id='" . $spouse_id[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_date[$i] = $rows[0]->getProperty('death_date');
        $spouse_death_place[$i] = $rows[0]->getProperty('death_place');
      }
    }

    $query_string = "MATCH (n:Person)-[:CHILD]->(id)-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m, id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $child_first_name[] = $rows[0]->getProperty('first_name');
      $child_last_name[] = $rows[0]->getProperty('last_name');
      $child_id[] = $rows[1]->getProperty('id');
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]-(m) WHERE n.id='" . $child_id[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_date[$i] = $rows[0]->getProperty('birth_date');
        $child_birth_place[$i] = $rows[0]->getProperty('birth_place');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]-(m) WHERE n.id='" . $child_id[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_date[$i] = $rows[0]->getProperty('death_date');
        $child_death_place[$i] = $rows[0]->getProperty('death_place');
      }
    }

    echo '<table  cellpadding="0" cellspacing="1" border="1">';
    echo '<tr><th> <th>id<th>Etunimet<th>Sukunimi<th>Syntym&auml;aika
              <th>Syntym&auml;paikka<th>Kuolinaika<th>Kuolinpaikka</tr>';
 
    echo "<tr><th>Henkil&ouml;:<td>" . $id . 
         "</td><td>" . $first_name .
         "</td><td>" . $last_name .
         "</td><td>" . $birth_date .
         "</td><td>" . $birth_place .
         "</td><td>" . $death_date .
         "</td><td>" . $death_place .
         "</td></tr>";
 
    echo "<tr><th>Is&auml;:<td><a href='readIndividData.php?id=" .
           $father_id . "'>" . $father_id . 
         "</a></td><td>" . $father_first_name .
         "</td><td>" . $father_last_name .
         "</td><td>" . $father_birth_date .
         "</td><td>" . $father_birth_place .
         "</td><td>" . $father_death_date .
         "</td><td>" . $father_death_place .
         "</td></tr>";
 
    echo "<tr><th>&Auml;iti:<td><a href='readIndividData.php?id=" .
           $mother_id . "'>" . $mother_id . 
         "</a></td><td>" . $mother_first_name .
         "</td><td>" . $mother_last_name .
         "</td><td>" . $mother_birth_date .
         "</td><td>" . $mother_birth_place .
         "</td><td>" . $mother_death_date .
         "</td><td>" . $mother_death_place .
         "</td></tr>";

    echo '<tr><th>Puoliso(t):<th>id<th>Etunimet<th>Sukunimi<th>Syntym&auml;aika 
              <th>Syntym&auml;paikka<th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $spouse_id[$i] . "'>" . $spouse_id[$i] .
       "</a></td><td>" . $spouse_first_name[$i] .
       "</td><td>" . $spouse_last_name[$i] .
       "</td><td>" . $spouse_birth_date[$i] .
       "</td><td>" . $spouse_birth_place[$i] .
       "</td><td>" . $spouse_death_date[$i] .
       "</td><td>" . $spouse_death_place[$i] .
       "</td></tr>";
    }

    echo '<tr><th>Lapset:<th>id<th>Etunimet<th>Sukunimi<th>Syntym&auml;aika 
              <th>Syntym&auml;paikka<th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($child_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $child_id[$i] . "'>" . $child_id[$i] .
       "</a></td><td>" . $child_first_name[$i] .
       "</td><td>" . $child_last_name[$i] .
       "</td><td>" . $child_birth_date[$i] .
       "</td><td>" . $child_birth_place[$i] .
       "</td><td>" . $child_death_date[$i] .
       "</td><td>" . $child_death_place[$i] .
       "</td></tr>";
    }

    echo "</table>";
  }
?>

</body>
</html>
