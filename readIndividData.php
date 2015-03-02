<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Sukulaisuussuhteet</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
<?php
  include 'inc/checkUserid.php';
  include "inc/start.php";
  include 'classes/DateConv.php';
  include "inc/dbconnect.php";

  echo "<h1>Läheiset sukulaisuussuhteet &mdash;";
  
  if(isset($_GET['id'])){
    // Tiedoston käsittelyn muuttujat
    $input_id = htmlentities($_GET['id']);

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:" . $userid . ") WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();
    $marriage_id = $spouse_id = $child_id = [];

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
      echo "<i>$first_name $last_name</i></h1>";

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
     * ------------------------- Show results ----------------------------
     */
    echo '<table class="tulos">';
      echo "<tr><th> </th><th>id</td><th>Etunimet</th><th>Sukunimet</th>
          <th>Syntynyt</th><th>Kuollut</th></tr>\n";
      echo "<tr><th><div class='right'>Henkilö</div></th><td>" . $id . "</td>";
      echo "<td><b>$first_name</b></td><td><b>$last_name</b>";
      if (isset($later_names)) {
        echo "<br /><i>myöh.</i> $later_names";
      }
      echo "</td><td>";
      if (isset($birth_date)) {
        echo DateConv::toDisplay($birth_date) . ' ';
      }
      if (isset($birth_place)) {
        echo $birth_place;
      }
      echo "</td><td>";
      if (isset($death_date)) {
        echo DateConv::toDisplay($death_date) . ' ';
      }
      if (isset($death_place)) {
        echo $death_place;
      }
      echo "</tr>\n";

      if (isset($todo_description)) {
        echo "<tr><th><div class='right'>Huomautus</th><td colspan='7'>$todo_description</td></tr>\n";
      }

      if (isset($father_id)) {
        echo "<tr><th><div class='right'>Isä</th><td><a href='readIndividData.php?id=" .
        $father_id . "'>" . $father_id . "</a></td>";
        echo "<td>$father_first_name</td><td>$father_last_name";
        if (isset($father_later_names)) {
          echo "<br /><i>myöh.</i> $father_later_names";
        }
        echo "</td><td>";
        if (isset($father_birth_date)) {
          echo DateConv::toDisplay($father_birth_date) . ' ';
        }
        if (isset($father_birth_place)) {
          echo $father_birth_place;
        }
        echo "</td><td>";
        if (isset($father_death_date)) {
          echo DateConv::toDisplay($father_death_date) . ' ';
        }
        if (isset($father_death_place)) {
          echo $father_death_place;
        }
        echo "</td></tr>\n";
      } else {
        echo "<tr><th><div class='right'>Isä</div></th><td colspan='6'>Ei tietoa</td></tr>\n";
      }

      if (isset($mother_id)) {
        echo "<tr><th><div class='right'>Äiti</div></th><td><a href='readIndividData.php?id=" .
        $mother_id . "'>" . $mother_id .
        "</a></td><td>" . $mother_first_name .
        "</td><td>" . $mother_last_name;
        if (isset($mother_later_names)) {
          echo "<br /><i>myöh.</i> $mother_later_names";
        }
        echo "</td><td>";
        if (isset($mother_birth_date)) {
          echo DateConv::toDisplay($mother_birth_date) . ' ';
        }
        if (isset($mother_birth_place)) {
          echo $mother_birth_place;
        }
        echo "</td><td>";
        if (isset($mother_death_date)) {
          echo DateConv::toDisplay($mother_death_date) . ' ';
        }
        if (isset($mother_death_place)) {
          echo $mother_death_place;
        }
        echo "</td></tr>\n";
      } else {
        echo "<tr><th><div class='right'>Äiti</div></th><td colspan='7'>Ei tietoa</td></tr>\n";
      }

      echo "<tr><th><div class='right'>Avioliitot</div></th><th colspan='2'>
          <th>Liitto</th><th>Vihitty</th><th>Eronnut</th></tr>\n";
      for ($i = 0; $i < sizeof($spouse_id); $i++) {
        echo "<tr><th></th><td colspan='2'></td><td>";
        echo (trim($married_status) != '') ? $married_status[$i] : '<i>avioliitto</i>';
        echo "</td><!--  $married_date[$i] -->";
        echo "<td>" . DateConv::toDisplay($married_date[$i]) . ' ';
        if (isset($married_place[$i])) { $married_place[$i]; }
        echo "</td><td>" . $divoced_status[$i] . ' '
        . DateConv::toDisplay($divoced_date[$i]);
        echo "</td></tr>\n";

        if (isset($marr_todo_description[$i])) {
          echo "<tr><th><div class='right'>Huomautus</div></th><td colspan='6'>"
          . $marr_todo_description[$i] . "</td>";
        }
        echo "</tr>\n";
      }

      echo "<tr><th><div class='right'>Puolisot</div></th><th>id</th><th>Etunimet</th>
        <th>Sukunimet</th><th>Syntynyt</th><th>Kuollut</th></tr>\n";
      for ($i = 0; $i < sizeof($spouse_id); $i++) {
        echo "<tr><th></th><td><a href='readIndividData.php?id=" .
        $spouse_id[$i] . "'>" . $spouse_id[$i] . "</a></td>";
        echo "<td>$spouse_first_name[$i]</td><td>$spouse_last_name[$i]";
        if (isset($spouse_later_names[$i])) {
          echo "<br /><i>myöh.</i> $spouse_later_names[$i]";
        }
        echo "</td><td>";
        if (isset($spouse_birth_date[$i])) {
          echo DateConv::toDisplay($spouse_birth_date[$i]) . ' ';
        }
        if (isset($spouse_birth_place[$i])) {
          echo $spouse_birth_place[$i];
        }
        echo "</td><td>";
        if (isset($spouse_death_date[$i])) {
          echo DateConv::toDisplay($spouse_death_date[$i]) . ' ';
        }
        if (isset($spouse_death_place[$i])) {
          echo $spouse_death_place[$i];
        }
        echo "</td></tr>\n";
      }

      echo "<tr><th><div class='right'>Lapset</div></th><th>id</th><th>Etunimet</th>
        <th>Sukunimet</th><th>Syntynyt</th><th>Kuollut</th></tr>\n";
      for ($i = 0; $i < sizeof($child_id); $i++) {
        echo "<tr><th></th><td><a href='readIndividData.php?id=" .
        $child_id[$i] . "'>" . $child_id[$i] . "</a></td>";
        echo "<td>" . $child_first_name[$i] . "</td><td>" . $child_last_name[$i];
        if (isset($child_later_names[$i])) {
          echo "<br /><i>myöh.</i> $child_later_names[$i]";
        }
        echo "</td><td>";
        if (isset($child_birth_date[$i])) {
          echo DateConv::toDisplay($child_birth_date[$i]) . ' ';
        }
        if (isset($child_birth_place[$i])) {
          echo $child_birth_place[$i];
        }
        echo "</td><td>";
        if (isset($child_death_date[$i])) {
          echo DateConv::toDisplay($child_death_date[$i]) . ' ';
        }
        if (isset($child_death_place[$i])) {
          echo $child_death_place[$i];
        }
        echo "</td></tr>\n";
      }

      echo "</table>";
    }
    ?>

    <h2>Muokkaustoiminnot</h2>
    <ul>
        <li>
            <form action="readHiskiLink.php" method="post" enctype="multipart/form-data">
                <p>Hiski-linkki
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit" value="Siirry Hiski-tietoon"/></p>
            </form>
        </li>
        <li>

            <form action="updateBirthData.php" method="get" enctype="multipart/form-data">
                <p>Syntymäaika ja paikka
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit"  value="Siirry syntymätietoon" /></p>
            </form>
        </li>
        <li>
            <form action="updateRepoData.php" method="get" enctype="multipart/form-data">
                <p>Arkisto (repository)
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit" value="Siirry arkistotietoon" /></p>
            </form>
        </li>
    </ul>
    <!-- End of content page -->

<?php include "inc/stop.php"; ?> 
