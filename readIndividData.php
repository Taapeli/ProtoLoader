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
    $input_id = $_GET['id'];

    include("openSukudb.php");

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person) WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $id . "' RETURN b";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_date = $rows[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d) WHERE n.id='" . $id . "' RETURN d";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
      $later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person)-[:TODO]->(t) WHERE n.id='" . $id . "' RETURN t";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $todo_description = $rows[0]->getProperty('description');
    }

    $query_string = "MATCH (n:Person)-[:FATHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_id = $rows[0]->getProperty('id');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $father_id . "' RETURN b";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_birth_date = $rows[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $father_id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d) WHERE n.id='" . $father_id . "' RETURN d";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $father_id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . $father_id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_first_name = $rows[0]->getProperty('first_name');
      $father_last_name = $rows[0]->getProperty('last_name');
      $father_later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person)-[:MOTHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_id = $rows[0]->getProperty('id');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $mother_id . "' RETURN b";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_birth_date = $rows[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $mother_id . "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d) WHERE n.id='" . $mother_id . "' RETURN d";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person)-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $mother_id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . $mother_id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_first_name = $rows[0]->getProperty('first_name');
      $mother_last_name = $rows[0]->getProperty('last_name');
      $mother_later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person)-[:MARRIED]->(m)<-[:MARRIED]-(s:Person) WHERE n.id='" . $id . "' RETURN m, s ORDER BY m.married_date";
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
    }

    for ($i=0; $i<sizeof($marriage_id); $i++) {
      $query_string = "MATCH (n:Marriage)-[:MARRIAGE_PLACE]->(p) WHERE n.id='" 
        . $marriage_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $married_place[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($marriage_id); $i++) {
      $query_string = "MATCH (n:Marriage)-[:TODO]->(t) WHERE n.id='" 
        . $marriage_id[$i] . "' RETURN t";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $marr_todo_description[] = $rows[0]->getProperty('description');
      }
    }

    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . 
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
      $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $spouse_id[$i] . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_date[$i] = $rows[0]->getProperty('birth_date');
      }

      $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_place[$i] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]->(d) WHERE n.id='" . $spouse_id[$i] . "' RETURN d";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_date[$i] = $rows[0]->getProperty('death_date');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    $query_string = "MATCH (n:Person)-[:CHILD]->(m) WHERE n.id='" . $id . 
      "' RETURN  m ORDER BY m.birth_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $child_id[] = $rows[0]->getProperty('id');
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $child_id[$i] . 
        "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_date[] = $rows[0]->getProperty('birth_date');
      }

      $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $child_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_place[] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]->(d) WHERE n.id='" . $child_id[$i] . 
      "' RETURN d";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_date[$i] = $rows[0]->getProperty('death_date');
      }

      $query_string = "MATCH (n:Person)-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . 
        $child_id[$i] . "' RETURN p";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person)-[:HAS_NAME]->(m) WHERE n.id='" . $child_id[$i] . "' RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_first_name[$i] = $rows[0]->getProperty('first_name');
        $child_last_name[$i] = $rows[0]->getProperty('last_name');
        $child_later_names[$i] = $rows[0]->getProperty('later_names');
      } 
    }

    echo '<table  cellpadding="0" cellspacing="1" border="1">';
    echo '<tr><th> <th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          <th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>';
 
    echo "<tr><th>Henkil&ouml;:<td>" . $id . 
         "</td><td>" . $first_name .
         "</td><td>" . $last_name .
         "</td><td>" . $later_names .
         "</td><td>" . $birth_date .
         "</td><td>" . $birth_place .
         "</td><td>" . $death_date .
         "</td><td>" . $death_place .
         "</td></tr>";

    echo "<tr><th>Huomautus:<td colspan='8'>" . $todo_description .
         "</td></tr>";

    echo "<tr><th>Is&auml;:<td><a href='readIndividData.php?id=" .
           $father_id . "'>" . $father_id . 
         "</a></td><td>" . $father_first_name .
         "</td><td>" . $father_last_name .
         "</td><td>" . $father_later_names .
         "</td><td>" . $father_birth_date .
         "</td><td>" . $father_birth_place .
         "</td><td>" . $father_death_date .
         "</td><td>" . $father_death_place .
         "</td></tr>";
 
    echo "<tr><th>&Auml;iti:<td><a href='readIndividData.php?id=" .
           $mother_id . "'>" . $mother_id . 
         "</a></td><td>" . $mother_first_name .
         "</td><td>" . $mother_last_name .
         "</td><td>" . $mother_later_names .
         "</td><td>" . $mother_birth_date .
         "</td><td>" . $mother_birth_place .
         "</td><td>" . $mother_death_date .
         "</td><td>" . $mother_death_place .
         "</td></tr>";

    echo '<tr><th>Avioliitot:<th><th>Vihitty<th>Vihkiaika<th>Vihkipaikka
          <th><th>Eronnut<th>Eroaika<th></tr>';
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td></td><td></td><td>" . $married_status[$i] .
       "</td><td>" . $married_date[$i] .
       "</td><td>" . $married_place[$i] .
       "</td><td> </td><td align='center'>" . $divoced_status[$i] .
       "</td><td>" . $divoced_date[$i] .
       "</td><td></td></tr>";
      echo "<tr><th>Huomautus:<td colspan='8'>" . $marr_todo_description[$i] .
         "</td></tr>";
    }


    echo '<tr><th>Puoliso(t):<th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          <th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $spouse_id[$i] . "'>" . $spouse_id[$i] .
       "</a></td><td>" . $spouse_first_name[$i] .
       "</td><td>" . $spouse_last_name[$i] .
       "</td><td>" . $spouse_later_names[$i] .
       "</td><td>" . $spouse_birth_date[$i] .
       "</td><td>" . $spouse_birth_place[$i] .
       "</td><td>" . $spouse_death_date[$i] .
       "</td><td>" . $spouse_death_place[$i] .
       "</td></tr>";
    }


    echo '<tr><th>Lapset:<th>id<th>Etunimet<th>Sukunimi<th>My&ouml;h. sukunimi
          <th>Syntym&auml;aika<th>Syntym&auml;paikka
          <th>Kuolinaika<th>Kuolinpaikka</tr>';
    for ($i=0; $i<sizeof($child_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $child_id[$i] . "'>" . $child_id[$i] .
       "</a></td><td>" . $child_first_name[$i] .
       "</td><td>" . $child_last_name[$i] .
       "</td><td>" . $child_later_names[$i] .
       "</td><td>" . $child_birth_date[$i] .
       "</td><td>" . $child_birth_place[$i] .
       "</td><td>" . $child_death_date[$i] .
       "</td><td>" . $child_death_place[$i] .
       "</td></tr>";
    }

    echo "</table>";
  }
?>

<form action="readHiskiLink.php" method="POST" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<p>Katso/yll&auml;pid&auml; Hiski-linkki.</p>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>

<form action="updateBirthData.php" method="GET" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<p>Yll&auml;pid&auml; syntym&auml;tietoa.</p>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>

<form action="updateRepoData.php" method="GET" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<p>Yll&auml;pid&auml; repo-tietoa.</p>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>

</body>
</html>
