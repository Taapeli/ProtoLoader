<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kantaan</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Verrataan kahta henkil&ouml;&auml; toisiinsa</h1>
<p>Luetaan neo4j-tietokannasta.</p>
<?php

  include "inc/dbconnect.php";

/*-------------------------- Tiedoston luku ----------------------------*/
/*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if ((isset($_GET['id'])) && (isset($_GET['id2']))) {
    // Tiedoston käsittelyn muuttujat
    $input_id = $_GET['id'];
    $input_id2 = $_GET['id2'];

    

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:user0498) WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
      $birth_date = $rows[0]->getProperty('birth_date');
      $death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:DEATH_PLACE]->(p) WHERE n.id='" . $id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
      $later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user0498)-[:TODO]->(t) WHERE n.id='" . $id . "' RETURN t";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $todo_description = $rows[0]->getProperty('description');
    }

    $query_string = "MATCH (n:Person:user0498)-[:FATHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_id = $rows[0]->getProperty('id');
      $father_birth_date = $rows[0]->getProperty('birth_date');
      $father_death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $father_id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:DEATH_PLACE]->(p) WHERE n.id='" . $father_id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(m) WHERE n.id='" . $father_id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_first_name = $rows[0]->getProperty('first_name');
      $father_last_name = $rows[0]->getProperty('last_name');
      $father_later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user0498)-[:MOTHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_id = $rows[0]->getProperty('id');
      $mother_birth_date = $rows[0]->getProperty('birth_date');
      $mother_death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $mother_id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:DEATH_PLACE]->(p) WHERE n.id='" . $mother_id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(m) WHERE n.id='" . $mother_id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_first_name = $rows[0]->getProperty('first_name');
      $mother_last_name = $rows[0]->getProperty('last_name');
      $mother_later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user0498)-[:MARRIED]->(m:user0498)<-[:MARRIED]-(s:Person:user0498) WHERE n.id='" . $id . "' RETURN m, s ORDER BY m.married_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $marriage_id[] = $rows[0]->getProperty('id');
      $married_date[] = $rows[0]->getProperty('married_date');
      $married_status[] = $rows[0]->getProperty('married_status');
      $divoced_date[] = $rows[0]->getProperty('divoced_date');
      $divoced_status[] = $rows[0]->getProperty('divoced_status');
      $spouse_id[] = $rows[1]->getProperty('id');
      $spouse_birth_date[] = $rows[1]->getProperty('birth_date');
      $spouse_death_date[] = $rows[1]->getProperty('death_date');
    }

    for ($i=0; $i<sizeof($marriage_id); $i++) {
      $query_string = "MATCH (n:Marriage:user0498)-[:MARRIAGE_PLACE]->(p) WHERE n.id='" 
        . $marriage_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $married_place[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($marriage_id); $i++) {
      $query_string = "MATCH (n:Marriage:user0498)-[:TODO]->(t) WHERE n.id='" 
        . $marriage_id[$i] . "' RETURN t";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $marr_todo_description[] = $rows[0]->getProperty('description');
      }
    }

    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(m) WHERE n.id='" . 
        $spouse_id[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_first_name[] = $rows[0]->getProperty('first_name');
        $spouse_last_name[] = $rows[0]->getProperty('last_name');
        $spouse_later_names[] = $rows[0]->getProperty('later_names');
      }
    }

    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_place[$i] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person:user0498)-[:DEATH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    $query_string = "MATCH (n:Person:user0498)-[:CHILD]->(m) WHERE n.id='" . $id . 
      "' RETURN  m ORDER BY m.birth_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $child_id[] = $rows[0]->getProperty('id');
      $child_birth_date[] = $rows[0]->getProperty('birth_date');
      $child_death_date[] = $rows[0]->getProperty('death_date');
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person:user0498)-[:BIRTH_PLACE]->(p) WHERE n.id='" . 
        $child_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_place[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person:user0498)-[:DEATH_PLACE]->(p) WHERE n.id='" . 
        $child_id[$i] . "' RETURN p";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]->(m) WHERE n.id='" . $child_id[$i] . "' RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_first_name[$i] = $rows[0]->getProperty('first_name');
        $child_last_name[$i] = $rows[0]->getProperty('last_name');
        $child_later_names[$i] = $rows[0]->getProperty('later_names');
      } 
    }

/* ------------------------------------------------------------------------------*/

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:user6321) WHERE n.id={id2} RETURN n";

    $query_array = array('id2' => $input_id2);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id2 = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
      $birth_date2 = $rows[0]->getProperty('birth_date');
      $death_date2 = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:DEATH_PLACE]->(p) WHERE n.id='" . $id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(m) WHERE n.id='" . $id2 . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name2 = $rows[0]->getProperty('first_name');
      $last_name2 = $rows[0]->getProperty('last_name');
      $later_names2 = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user6321)-[:TODO]->(t) WHERE n.id='" . $id2 . "' RETURN t";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $todo_description2 = $rows[0]->getProperty('description');
    }

    $query_string = "MATCH (n:Person:user6321)-[:FATHER]->(id) WHERE n.id='" . $id2 . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_id2 = $rows[0]->getProperty('id');
      $father_birth_date2 = $rows[0]->getProperty('birth_date');
      $father_death_date2 = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $father_id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_birth_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:DEATH_PLACE]->(p) WHERE n.id='" . $father_id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_death_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(m) WHERE n.id='" . $father_id2 . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_first_name2 = $rows[0]->getProperty('first_name');
      $father_last_name2 = $rows[0]->getProperty('last_name');
      $father_later_names2 = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user6321)-[:MOTHER]->(id) WHERE n.id='" . $id2 . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_id2 = $rows[0]->getProperty('id');
      $mother_birth_date2 = $rows[0]->getProperty('birth_date');
      $mother_death_date2 = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $mother_id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_birth_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:DEATH_PLACE]->(p) WHERE n.id='" . $mother_id2 . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_death_place2 = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(m) WHERE n.id='" . $mother_id2 . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_first_name2 = $rows[0]->getProperty('first_name');
      $mother_last_name2 = $rows[0]->getProperty('last_name');
      $mother_later_names2 = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:user6321)-[:MARRIED]->(m:user6321)<-[:MARRIED]-(s:Person:user6321) WHERE n.id='" . $id2 . "' RETURN m, s ORDER BY m.married_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $marriage_id2[] = $rows[0]->getProperty('id');
      $married_date2[] = $rows[0]->getProperty('married_date');
      $married_status2[] = $rows[0]->getProperty('married_status');
      $divoced_date2[] = $rows[0]->getProperty('divoced_date');
      $divoced_status2[] = $rows[0]->getProperty('divoced_status');
      $spouse_id2[] = $rows[1]->getProperty('id');
      $spouse_birth_date2[] = $rows[1]->getProperty('birth_date');
      $spouse_death_date2[] = $rows[1]->getProperty('death_date');
    }

    for ($i=0; $i<sizeof($marriage_id2); $i++) {
      $query_string = "MATCH (n:Marriage:user6321)-[:MARRIAGE_PLACE]->(p) WHERE n.id='" 
        . $marriage_id2[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $married_place2[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($marriage_id2); $i++) {
      $query_string = "MATCH (n:Marriage:user6321)-[:TODO]->(t) WHERE n.id='" 
        . $marriage_id2[$i] . "' RETURN t";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $marr_todo_description2[] = $rows[0]->getProperty('description');
      }
    }

    for ($i=0; $i<sizeof($spouse_id2); $i++) {
      $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(m) WHERE n.id='" . 
        $spouse_id2[$i] . "' RETURN m";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_first_name2[] = $rows[0]->getProperty('first_name');
        $spouse_last_name2[] = $rows[0]->getProperty('last_name');
        $spouse_later_names2[] = $rows[0]->getProperty('later_names');
      }
    }

    for ($i=0; $i<sizeof($spouse_id2); $i++) {
      $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $spouse_id2[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_place2[$i] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person:user6321)-[:DEATH_PLACE]->(p) WHERE n.id='" . $spouse_id2[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_place2[$i] = $rows[0]->getProperty('name');
      }
    }

    $query_string = "MATCH (n:Person:user6321)-[:CHILD]->(m) WHERE n.id='" . $id2 . 
      "' RETURN  m ORDER BY m.birth_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $child_id2[] = $rows[0]->getProperty('id');
      $child_birth_date2[] = $rows[0]->getProperty('birth_date');
      $child_death_date2[] = $rows[0]->getProperty('death_date');
    }

    for ($i=0; $i<sizeof($child_id2); $i++) {
      $query_string = "MATCH (n:Person:user6321)-[:BIRTH_PLACE]->(p) WHERE n.id='" . 
        $child_id2[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_place2[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id2); $i++) {
      $query_string = "MATCH (n:Person:user6321)-[:DEATH_PLACE]->(p) WHERE n.id='" . 
        $child_id2[$i] . "' RETURN p";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_place2[$i] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id2); $i++) {
      $query_string = "MATCH (n:Person:user6321)-[:HAS_NAME]->(m) WHERE n.id='" . $child_id2[$i] . "' RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_first_name2[$i] = $rows[0]->getProperty('first_name');
        $child_last_name2[$i] = $rows[0]->getProperty('last_name');
        $child_later_names2[$i] = $rows[0]->getProperty('later_names');
      } 
    }

    echo "<table  cellpadding='0' cellspacing='1' border='1'><tr><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";
    echo "<tr><th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi</tr>";
 
    echo "<tr><td>" . $id . 
         "</td><td>" . $first_name .
         "</td><td>" . $last_name .
         "</td><td>" . $later_names .
         "</td></tr>";

    echo "<tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>";

    echo "<tr></td><td>" . $birth_date .
         "</td><td>" . $birth_place .
         "</td><td>" . $death_date .
         "</td><td>" . $death_place .
         "</td></tr>";

    echo "<tr><th>Is&auml;:</tr>";

    echo "<tr><td>" . $father_id . 
         "</td><td>" . $father_first_name .
         "</td><td>" . $father_last_name .
         "</td><td>" . $father_later_names .
         "</td></tr>";

    echo "<tr><td>" . $father_birth_date .
         "</td><td>" . $father_birth_place .
         "</td><td>" . $father_death_date .
         "</td><td>" . $father_death_place .
         "</td></tr>";
 
    echo "<tr><th>&Auml;iti:</tr>";

    echo "<tr><td>" . $mother_id . 
         "</td><td>" . $mother_first_name .
         "</td><td>" . $mother_last_name .
         "</td><td>" . $mother_later_names .
         "</td></tr>";
 
    echo "<tr><td>" . $mother_birth_date .
         "</td><td>" . $mother_birth_place .
         "</td><td>" . $mother_death_date .
         "</td><td>" . $mother_death_place .
         "</td></tr>";

    echo "</table></td><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";
    echo "<tr><th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi</tr>";
 
    echo "<tr><td>" . $id2 . 
         "</td><td>" . $first_name2 .
         "</td><td>" . $last_name2 .
         "</td><td>" . $later_names2 .
         "</td></tr>";

    echo "<tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>";

    echo "<tr></td><td>" . $birth_date2 .
         "</td><td>" . $birth_place2 .
         "</td><td>" . $death_date2 .
         "</td><td>" . $death_place2 .
         "</td></tr>";

    echo "<tr><th>Is&auml;:</tr>";

    echo "<tr><td>" . $father_id2 . 
         "</td><td>" . $father_first_name2 .
         "</td><td>" . $father_last_name2 .
         "</td><td>" . $father_later_names2 .
         "</td></tr>";

    echo "<tr><td>" . $father_birth_date2 .
         "</td><td>" . $father_birth_place2 .
         "</td><td>" . $father_death_date2 .
         "</td><td>" . $father_death_place2 .
         "</td></tr>";
 
    echo "<tr><th>&Auml;iti:</tr>";

    echo "<tr><td>" . $mother_id2 . 
         "</td><td>" . $mother_first_name2 .
         "</td><td>" . $mother_last_name2 .
         "</td><td>" . $mother_later_names2 .
         "</td></tr>";
 
    echo "<tr><td>" . $mother_birth_date2 .
         "</td><td>" . $mother_birth_place2 .
         "</td><td>" . $mother_death_date2 .
         "</td><td>" . $mother_death_place2 .
         "</td></tr>";

    echo "</table></td></tr></table><br>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'><tr><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";

    echo "<tr><th>Avioliitot:</tr>";

    echo "<tr><th><th>Vihkiaika<th>Vihkipaikka<th><th>Eroaika</tr>";
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td>" . $married_status[$i] .
       "</td><td>" . $married_date[$i] .
       "</td><td>" . $married_place[$i] .
       "</td><td align='center'>" . $divoced_status[$i] .
       "</td><td>" . $divoced_date[$i] .
       "</td></tr>";
    }

    echo "<tr><th>Puoliso(t):</tr>";

    echo "<tr><th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          </tr><tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>";
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td>" . $spouse_id[$i] .
       "</td><td>" . $spouse_first_name[$i] .
       "</td><td>" . $spouse_last_name[$i] .
       "</td><td>" . $spouse_later_names[$i] .
       "</td></tr><tr><td>" . $spouse_birth_date[$i] .
       "</td><td>" . $spouse_birth_place[$i] .
       "</td><td>" . $spouse_death_date[$i] .
       "</td><td>" . $spouse_death_place[$i] .
       "</td></tr>";
    }

    echo "</table></td><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";
    echo "<tr><th>Avioliitot:</tr>";

    echo "<tr><th><th>Vihkiaika<th>Vihkipaikka<th><th>Eroaika</tr>";
    for ($i=0; $i<sizeof($spouse_id2); $i++) {
      echo "<tr><td>" . $married_status2[$i] .
       "</td><td>" . $married_date2[$i] .
       "</td><td>" . $married_place2[$i] .
       "</td><td align='center'>" . $divoced_status2[$i] .
       "</td><td>" . $divoced_date2[$i] .
       "</td></tr>";
    }


    echo "<tr><th>Puoliso(t):</tr>";

    echo "<tr><th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          </tr><tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>";
    for ($i=0; $i<sizeof($spouse_id2); $i++) {
      echo "<tr><td>" . $spouse_id2[$i] .
       "</td><td>" . $spouse_first_name2[$i] .
       "</td><td>" . $spouse_last_name2[$i] .
       "</td><td>" . $spouse_later_names2[$i] .
       "</td></tr><tr><td>" . $spouse_birth_date2[$i] .
       "</td><td>" . $spouse_birth_place2[$i] .
       "</td><td>" . $spouse_death_date2[$i] .
       "</td><td>" . $spouse_death_place2[$i] .
       "</td></tr>";
    }

    echo "</table></td></tr></table><br>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'><tr><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";

    echo '<tr><th>Lapset:<th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          </tr><tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($child_id); $i++) {
      echo "<tr><td></td><td>" . $child_id[$i] .
       "</td><td>" . $child_first_name[$i] .
       "</td><td>" . $child_last_name[$i] .
       "</td><td>" . $child_later_names[$i] .
       "</td></tr><tr><td>" . $child_birth_date[$i] .
       "</td><td>" . $child_birth_place[$i] .
       "</td><td>" . $child_death_date[$i] .
       "</td><td>" . $child_death_place[$i] .
       "</td></tr>";
    }

    echo "</table></td><td>";

    echo "<table  cellpadding='0' cellspacing='1' border='1'>";
    echo '<tr><th>Lapset:<th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          </tr><tr><th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($child_id2); $i++) {
      echo "<tr><td></td><td>" . $child_id2[$i] .
       "</td><td>" . $child_first_name2[$i] .
       "</td><td>" . $child_last_name2[$i] .
       "</td><td>" . $child_later_names2[$i] .
       "</td></tr><tr><td>" . $child_birth_date2[$i] .
       "</td><td>" . $child_birth_place2[$i] .
       "</td><td>" . $child_death_date2[$i] .
       "</td><td>" . $child_death_place2[$i] .
       "</td></tr>";
    }

    echo "</table></td></tr></table>";

  }
?>

</body>
</html>
