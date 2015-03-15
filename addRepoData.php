<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - lähdetietojen päivitys</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
<?php
include 'inc/checkUserid.php';
include "inc/start.php";
include "inc/dbconnect.php";

        /*
         * -- Content page starts here -->
         */

echo '<h1>Päivitetyt tiedot</h1>';

  if ((isset($_POST['id'])) && (isset($_POST['repo'])) && (isset($_POST['source']))) {
    // Tiedoston käsittelyn muuttujat
    $input_id = htmlentities($_POST['id']);
    $input_repo = htmlentities($_POST['repo']);
    $input_source = htmlentities($_POST['source']);
    $input_page = "";

    if (isset($_POST['page'])) {
      $input_page = $_POST['page'];
    }

    $query_string = "MATCH (r:Repo:" . $userid . " {name:{repo}}) RETURN r";
    $query_array = array('repo' => $input_repo);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();
    $repo_source = $repo_name_only = [];

    if (sizeof($result) > 0) { // repo exists
      foreach ($result as $rows)
      {
        $repo_id = $rows[0]->getProperty('id');
      }
 
      $query_string = "MATCH (r:Repo:" . $userid . 
        " {id:{repo_id}})-[:REPO_SOURCE]-(s:Source {title:{source}}) RETURN s";

      $query_array = array('source' => $input_source, 'repo_id' => $repo_id);

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
      $result = $query->getResultSet();

      if (sizeof($result) > 0) { // source exists
        foreach ($result as $rows)
        {
          $source_id = $rows[0]->getProperty('id');
        }

        $query_string = "MATCH (n:Person:" . $userid . 
          " {id:{id}}), (s:Source:" . $userid . " {id:{source_id}}) 
          MERGE (n)-[:BIRTH]->(b)-[p:BIRTH_SOURCE]->(s) SET p.page={page}";

        $query_array = array('id' => $input_id, 'source_id' => $source_id, 'page' => $input_page);

        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
        $result = $query->getResultSet();
      }
      else { // old repo, new repo's source
 
        $query_string = "MATCH (r:Repo:" . $userid . 
          " {id:{repo_id}})-[:REPO_SOURCE]-(s:Source) RETURN max(s.id)";

        $query_array = array('repo_id' => $repo_id);

        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
        $result = $query->getResultSet();

        if (sizeof($result) > 0) { // repo has at least one source
          foreach ($result as $rows)
          {
            $source_id_max = $rows[0];
          }
        }
        else { // repo has not sources
          $source_id_max = "S0000";
        }

        $source_id_int = intval(substr($source_id_max, 1));
        $source_id_int++;
        $source_id = "S" . $source_id_int;
 
        $query_string = "MATCH (n:Person:" . $userid . 
          " {id:{id}}), (r:Repo:" . $userid . " {id:{repo_id}}) 
          MERGE (s:Source:" . $userid . " {id:{source_id}, title:{source}}) 
          MERGE (r)-[:REPO_SOURCE]->(s) 
          MERGE (n)-[:BIRTH]->(b)-[p:BIRTH_SOURCE]->(s) SET p.page={page}";

        $query_array = array('id' => $input_id, 
            'source_id' => $source_id, 
            'source' => $input_source, 
            'repo_id' => $repo_id, 
            'page' => $input_page);

        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
        $result = $query->getResultSet();
      }
    }
    else { // new repo and repo's source
      $query_string = "MATCH (r:Repo:" . $userid . ") RETURN max(r.id)";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      if (sizeof($result) > 0) { // at least one repo exists
        foreach ($result as $rows)
        {
          $repo_id_max = $rows[0];
        }
      }
      else { // no repos exist in the data base
        $repo_id_max = "R0000";
      }
      $repo_id_int = intval(substr($repo_id_max, 1));
      $repo_id_int++;
      $repo_id = "R" . $repo_id_int;
      $source_id = "S0001";
 
      $query_string = "MATCH (n:Person:" . $userid . " {id:{id}}) 
        MERGE (r:Repo:" . $userid . " {id:{repo_id}, name:{repo}}) 
        MERGE (s:Source:" . $userid . " {id:{source_id}, title:{source}}) 
        MERGE (r)-[:REPO_SOURCE]->(s) 
        MERGE (n)-[:BIRTH]->(b)-[p:BIRTH_SOURCE]->(s) SET p.page={page}";

      $query_array = array('id' => $input_id, 
          'source_id' => $source_id, 
          'source' => $input_source, 
          'repo_id' => $repo_id, 
          'repo' => $input_repo, 
          'page' => $input_page);

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
      $result = $query->getResultSet();
    }  

    // Neo4j parameter {id} is used to avoid hacking injection
    $query_string = "MATCH (n:Person:" . $userid . ") WHERE n.id={id} RETURN n";

    $query_array = array('id' => $input_id);

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
    }

    $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b) WHERE n.id='" . $id . "' RETURN b";

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
      ")-[:HAS_NAME]-(m) WHERE n.id='" . $id . "' RETURN m";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $first_name = $rows[0]->getProperty('first_name');
      $last_name = $rows[0]->getProperty('last_name');
      $later_names = $rows[0]->getProperty('later_names');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b)-[p:BIRTH_SOURCE]-(s:Source)-[:REPO_SOURCE]-(r:Repo)
      WHERE n.id='" . $id . "' RETURN r,s,p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_name[] = $rows[0]->getProperty('name');
      $repo_source[] = $rows[1]->getProperty('title');
      $repo_page[] = $rows[2]->getProperty('page');
    }

    $query_string = "MATCH (n:Person:" . $userid . 
      ")-[:BIRTH]->(b)-[p:BIRTH_REPO]-(r:Repo) 
      WHERE n.id='" . $id . "' RETURN r, p";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_name_only[] = $rows[0]->getProperty('name');
      $repo_page_only[] = $rows[1]->getProperty('page');
    }

    echo '<table  class="tulos">';
    echo '<tr><th>id<th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>
          <th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';

    echo "<tr><td>" . $id .
         "</td><td> " . $first_name .
         "</td><td> " . $last_name .
         "</td><td> " . $later_names .
         "</td><td> " . $birth_date .
         "</td><td> " . $birth_place .
         "</td></tr>";

    echo "<tr><th></th><th colspan='4'>Arkisto ja lähde</th><th>Arkistoviite</th></tr>";

    for ($i=0; $i<sizeof($repo_source); $i++) {
      echo "<tr><td rowspan='2'></td><td colspan='5'> " . $repo_name[$i] .
         "</td></tr>";

      echo "<tr><td colspan='4'> " . $repo_source[$i] .
         "</td><td>" . $repo_page[$i] . "</td></tr>";
    }

    for ($i=0; $i<sizeof($repo_name_only); $i++) {
      echo "<tr><td></td><td colspan='4'> " . $repo_name_only[$i] .
         "</td><td>" . $repo_page_only[$i] . "</td></tr>";
    }
 
    echo "</table>";

  }
  
  /*
   * --- End of content page ---
   */
include "inc/stop.php";
