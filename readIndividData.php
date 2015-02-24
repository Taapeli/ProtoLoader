<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Sululaisuussuhteet</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Taapeli testiluku</h1>
<p>Luetaan neo4j-tietokannasta.</p>
<?php
  include 'checkUserid.php';
  include "inc/start.php";
  include 'classes/DateConv.php';
  include "inc/dbconnect.php";

  if(isset($_GET['id'])){
    // Tiedoston käsittelyn muuttujat
    $input_id = $_GET['id'];

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:" . $userid . ") WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b) WHERE n.id='" . $id . "' RETURN b";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_date = $rows[0]->getProperty('birth_date');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $birth_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:DEATH]->(d) WHERE n.id='" . $id . "' RETURN d";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_date = $rows[0]->getProperty('death_date');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $id . 
      "' RETURN p";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $death_place = $rows[0]->getProperty('name');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:HAS_NAME]->(m) WHERE n.id='" . $id . "' RETURN m";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
      $later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:TODO]->(t) WHERE n.id='" . $id . "' RETURN t";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $todo_description = $rows[0]->getProperty('description');
    }

    /*
     * Isä
     */
    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:FATHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $father_id = $rows[0]->getProperty('id');
    }

    if (isset($father_id)) {
        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:BIRTH]->(b) WHERE n.id='" . $father_id . "' RETURN b";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $father_birth_date = $rows[0]->getProperty('birth_date');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $father_id . 
          "' RETURN p";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $father_birth_place = $rows[0]->getProperty('name');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:DEATH]->(d) WHERE n.id='" . $father_id . "' RETURN d";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $father_death_date = $rows[0]->getProperty('death_date');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $father_id . 
          "' RETURN p";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $father_death_place = $rows[0]->getProperty('name');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:HAS_NAME]->(m) WHERE n.id='" . $father_id . "' RETURN m";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $father_first_name = $rows[0]->getProperty('first_name');
          $father_last_name = $rows[0]->getProperty('last_name');
          $father_later_names = $rows[0]->getProperty('later_names');
        }
    }
    
    /*
     * Äiti
     */
    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:MOTHER]->(id) WHERE n.id='" . $id . "' RETURN id";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $mother_id = $rows[0]->getProperty('id');
    }

    if (isset($mother_id)) {
        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:BIRTH]->(b) WHERE n.id='" . $mother_id . "' RETURN b";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $mother_birth_date = $rows[0]->getProperty('birth_date');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $mother_id . "' RETURN p";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $mother_birth_place = $rows[0]->getProperty('name');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:DEATH]->(d) WHERE n.id='" . $mother_id . "' RETURN d";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $mother_death_date = $rows[0]->getProperty('death_date');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $mother_id . 
          "' RETURN p";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $mother_death_place = $rows[0]->getProperty('name');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          ")-[:HAS_NAME]->(m) WHERE n.id='" . $mother_id . "' RETURN m";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows)
        {
          $mother_first_name = $rows[0]->getProperty('first_name');
          $mother_last_name = $rows[0]->getProperty('last_name');
          $mother_later_names = $rows[0]->getProperty('later_names');
        }
    }
    
    /*
     * Avioliitot
     */
    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:MARRIED]->(m)<-[:MARRIED]-(s:Person:" . $userid . 
      ") WHERE n.id='" . $id . 
      "' RETURN m, s ORDER BY m.married_date";
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
      $query_string = "MATCH (n:Marriage:" . $userid . 
        ")-[:MARRIAGE_PLACE]->(p) WHERE n.id='" 
        . $marriage_id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $married_place[] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($marriage_id); $i++) {
      $query_string = "MATCH (n:Marriage:" . $userid . 
        ")-[:TODO]->(t) WHERE n.id='" . $marriage_id[$i] . "' RETURN t";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $marr_todo_description[] = $rows[0]->getProperty('description');
      }
    }

    /*
     * Puoliso
     */
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:HAS_NAME]->(m) WHERE n.id='" . 
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

    /*
     * Lapset
     */
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:BIRTH]->(b) WHERE n.id='" . $spouse_id[$i] . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_date[$i] = $rows[0]->getProperty('birth_date');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_birth_place[$i] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:DEATH]->(d) WHERE n.id='" . $spouse_id[$i] . "' RETURN d";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_date[$i] = $rows[0]->getProperty('death_date');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . $spouse_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $spouse_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:CHILD]->(m) WHERE n.id='" . $id . 
      "' RETURN  m ORDER BY m.birth_date";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $child_id[] = $rows[0]->getProperty('id');
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:BIRTH]->(b) WHERE n.id='" . $child_id[$i] . 
        "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_date[] = $rows[0]->getProperty('birth_date');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" . $child_id[$i] . 
        "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_birth_place[] = $rows[0]->getProperty('name');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:DEATH]->(d) WHERE n.id='" . $child_id[$i] . 
      "' RETURN d";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_date[$i] = $rows[0]->getProperty('death_date');
      }

      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:DEATH]->(d)-[:DEATH_PLACE]->(p) WHERE n.id='" . 
        $child_id[$i] . "' RETURN p";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_death_place[$i] = $rows[0]->getProperty('name');
      }
    }

    for ($i=0; $i<sizeof($child_id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . 
        ")-[:HAS_NAME]->(m) WHERE n.id='" . $child_id[$i] . "' RETURN m";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $child_first_name[$i] = $rows[0]->getProperty('first_name');
        $child_last_name[$i] = $rows[0]->getProperty('last_name');
        $child_later_names[$i] = $rows[0]->getProperty('later_names');
      } 
    }

    /*
     * Tulostus
     */
    echo '<table class="tulos">';
    echo '<tr><th> </th><th>id</td><th>Etunimet</th><th>Sukunimi</th>
          <th>Myöh. sukunimi</th><th>Syntymäaika, paikka</th>
          <th>Kuolinaika, paikka</th></tr>';
 
    echo "<tr><th>Henkilö:<td>" . $id . 
         "</td><td>" . $first_name .
         "</td><td>" . $last_name .
         "</td><td>" . $later_names .
         "</td><td>" . $birth_date . ' ' . $birth_place .
         "</td><td>" . $death_date . ' ' . $death_place .
         "</td></tr>";

    if (isset($todo_description)) {
      echo "<tr><th>Huomautus:<td colspan='8'>$todo_description</td></tr>";
    }

    if (isset($father_id)) {
        echo "<tr><th>Isä:<td><a href='readIndividData.php?id=" .
               $father_id . "'>" . $father_id . "</a></td>";
        echo "<td>$father_first_name</td><td>$father_last_name</td><td>";
        if (isset($father_later_names)) { echo $father_later_names; }
        echo "</td><td>";
        if (isset($father_birth_date)) { echo $father_birth_date . ' '; }
        if (isset($father_birth_place)) { echo $father_birth_place; }
        echo "</td><td>";
        if (isset($father_death_date)) { echo $father_death_date . ' '; }
        if (isset($father_death_date)) { echo $father_death_place; }
        echo "</tr>";
    } else {
        echo "<tr><td colspan='8'>Ei tietoa isästä</td></tr>\n";
    }
 
    if (isset($mother_id)) {
        echo "<tr><th>Äiti:<td><a href='readIndividData.php?id=" .
               $mother_id . "'>" . $mother_id . 
             "</a></td><td>" . $mother_first_name .
             "</td><td>" . $mother_last_name .
             "</td><td>" . $mother_later_names .
             "</td><td>" . $mother_birth_date . ' ' . $mother_birth_place .
             "</td><td>" . $mother_death_date . ' ' . $mother_death_place .
             "</td></tr>";
        } else {
        echo "<tr><td colspan='8'>Ei tietoa äidistä</td></tr>\n";
    }

    echo '<tr><th>Avioliitot:</th><th colspan="4">
          <th>Vihitty</th><th>Vihkiaika, paikka</th>
          <th>Eronnut</th><th>Eroaika</th></tr>';
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td></td><td colspan='4'></td>";
      echo "<td>" . $married_status[$i];
      echo "</td><td>" . $married_date[$i] . ' ' . $married_place[$i];
      echo "</td><td align='center'>" . $divoced_status[$i] . ' ' . $divoced_date[$i];
      echo "</td></tr>";
      echo "<tr><th>Huomautus:<td colspan='7'>" . $marr_todo_description[$i] .
         "</td></tr>";
    }


    echo '<tr><th>Puoliso(t):</th><th>id</th><th>Etunimet</th><th>Sukunimi</th>
          <th>Myöh. sukunimi</th>
          <th>Syntymäaika, paikka</th><th>Kuolinaika, paikka</th></tr>';
    for ($i=0; $i<sizeof($spouse_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $spouse_id[$i] . "'>" . $spouse_id[$i] .
       "</a></td><td>" . $spouse_first_name[$i] .
       "</td><td>" . $spouse_last_name[$i] .
       "</td><td>" . $spouse_later_names[$i] .
       "</td><td>" . $spouse_birth_date[$i] . ' ' . $spouse_birth_place[$i] .
       "</td><td>" . $spouse_death_date[$i] . ' ' . $spouse_death_place[$i] .
       "</td></tr>";
    }


    echo '<tr><th>Lapset:</th><th>id</th><th>Etunimet</th><th>Sukunimi</th>
          <th>Myöh. sukunimi</th>
          <th>Syntymäaika, paikka</th><th>Kuolinaika, paikka</th></tr>';
    for ($i=0; $i<sizeof($child_id); $i++) {
      echo "<tr><td></td><td><a href='readIndividData.php?id=" .
         $child_id[$i] . "'>" . $child_id[$i] .
       "</a></td><td>" . $child_first_name[$i] .
       "</td><td>" . $child_last_name[$i] .
       "</td><td>" . $child_later_names[$i] .
       "</td><td>" . $child_birth_date[$i] . ' ' . $child_birth_place[$i] .
       "</td><td>" . $child_death_date[$i] . ' ' . $child_death_place[$i] .
       "</td></tr>";
    }

    echo "</table>";
  }
?>

<form action="readHiskiLink.php" method="post" enctype="multipart/form-data"></p>
<div class="form">
<p>Katso/ylläpidä Hiski-linkkiä
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="submit" value="Siirry Hiski-tietoon"/></p>
</div>
</form>

<form action="updateBirthData.php" method="GET" enctype="multipart/form-data"></p>
<div class="form">
<p>Ylläpidä syntymätietoa
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="submit"  value="Siirry syntymätietoon" /></p>
</div>
</form>

<form action="updateRepoData.php" method="GET" enctype="multipart/form-data"></p>
<div class="form">
<p>Ylläpidä repository-tietoa
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="submit" value="Siirry repository-tietoon" /></p>
</div>

<!-- End of content page -->

<?php include "inc/stop.php"; ?> 
