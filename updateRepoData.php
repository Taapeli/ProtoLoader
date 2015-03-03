<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - arkistoviittauksen muokkaus</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
        <?php
        include 'inc/checkUserid.php';
        include 'inc/start.php';
        include 'classes/DateConv.php';
        include 'inc/dbconnect.php';
        include 'inc/getRepositories.php';

        /*
         * -- Content page starts here -->
         */

        echo "<h1>Henkilön tietojen arkistoviite</h1>";
        if (isset($_GET['id'])) {
          // Tiedoston käsittelyn muuttujat
          $input_id = htmlentities($_GET['id']);

          // Neo4j parameter {id} is used to avoid hacking injection
          $query_string = "MATCH (n:Person:" . $userid . ") WHERE n.id={id} RETURN n";
          $query_array = array('id' => $input_id);

          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
          $result = $query->getResultSet();
          $repo_source = $repo_name_only = [];

          foreach ($result as $rows) {
            $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
          }

          $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b) "
                  . "WHERE n.id='" . $id . "' RETURN b";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
          $result = $query->getResultSet();

          foreach ($result as $rows) {
            $birth_date = $rows[0]->getProperty('birth_date');
          }

          $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) "
                  . "WHERE n.id='" . $id . "' RETURN p";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
          $result = $query->getResultSet();

          foreach ($result as $rows) {
            $birth_place = $rows[0]->getProperty('name');
          }

          $query_string = "MATCH (n:Person:" . $userid . ")-[:HAS_NAME]-(m) "
                  . "WHERE n.id='" . $id . "' RETURN m";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
          $result = $query->getResultSet();

          foreach ($result as $rows) {
            $first_name = $rows[0]->getProperty('first_name');
            $last_name = $rows[0]->getProperty('last_name');
            $later_names = $rows[0]->getProperty('later_names');
          }

          $query_string = "MATCH (n:Person:" . $userid . 
                    ")-[:BIRTH]->(b)-[p:BIRTH_SOURCE]->(s:Source)<-[:REPO_SOURCE]-(r:Repo) "
                  . "WHERE n.id='" . $id . "' RETURN r, s, p";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
          $result = $query->getResultSet();

          foreach ($result as $rows) {
            $repo_id[] = $rows[0]->getProperty('id');
            $repo_name[] = $rows[0]->getProperty('name');
            $repo_source_id[] = $rows[1]->getProperty('id');
            $repo_source[] = $rows[1]->getProperty('title');
            $repo_page[] = $rows[2]->getProperty('page');
          }

          $query_string = "MATCH (n:Person:" . $userid . 
                    ")-[:BIRTH]->(b)-[p:BIRTH_REPO]-(m:Repo) "
                  . "WHERE n.id='" . $id . "' RETURN m, p";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

          $result = $query->getResultSet();

          foreach ($result as $rows) {
            $repo_id_only[] = $rows[0]->getProperty('id');
            $repo_name_only[] = $rows[0]->getProperty('name');
            $repo_page_only[] = $rows[1]->getProperty('page');
          }

          echo '<table  class="tulos">';
          echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>'
          . '<th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';

          echo "<tr><td>" . $id .
          "</td><td> " . $first_name .
          "</td><td> " . $last_name .
          "</td><td> " . $later_names .
          "</td><td> " . $birth_date .
          "</td><td> " . $birth_place .
          "</td></tr>";

          echo "<tr><th> <th colspan='4'>Arkisto ja lähde</th><th>Lainaus</th></tr>";

          for ($i = 0; $i < sizeof($repo_source); $i++) {
            echo "<tr><td rowspan='2'><a href='disconnectRepo.php?id=" . $id .
            "&repoid=" . $repo_id[$i] . "&sourceid=" . $repo_source_id[$i] .
            "'>Poista</a></td><td colspan='4'> " . $repo_name[$i] .
            "</td></tr>";

            echo "<tr><td colspan='4'> " . $repo_source[$i] .
            "</td><td>" . $repo_page[$i] . "</td></tr>";
          }

          for ($i = 0; $i < sizeof($repo_name_only); $i++) {
            echo "<tr><td><a href='disconnectRepo.php?id=" . $id .
            "&repoid=" . $repo_id_only[$i] .
            "'>Poista</a></td><td colspan='4'> " . $repo_name_only[$i] .
            "</td><td>" . $repo_page_only[$i] . "</td></tr>";
          }

          echo "</table>";
        }
        /*
         * Get Sources for creating new references
         */
        $source_list = getSources($sukudb);
        ?>

        <h2>Toiminnot</h2>
        <div>
            <form action="addRepoData.php" method="post" enctype="multipart/form-data">
                <h3>Lisää uusi viite olemassa oleviin lähteisiin</h3>
                <p><input type="hidden" name="id" value="<?php echo $id; ?>" />
                    Lähde: 
                    <select>
            <?php
                foreach ($source_list as $srow) {
                  echo '<option value="' . $srow[0] . '">' . $srow[1] . '</option>';
                }
            ?>
                    </select> </p>
                <p><input type="hidden" name="id" value="<?php echo $id; ?>" />
                    Lainaus (esim. sivunumero): 
                    <input type="text" name="page" /></p>
                <p class="right"><input type="submit" value="Talleta" /></p>
            </form>

        <form action="addRepoData.php" method="post" enctype="multipart/form-data">
            <h3>Lisää viite uuteen arkistoon ja lähteeseen antamalla kaikki tiedot</h3>
            <p><input type="hidden" name="id" value="<?php echo $id; ?>" />
                Arkisto: 
                <input type="text" 
                       value="Hangon seurakunnan arkisto" 
                       size="60" name="repo" /></p>
            <p>Lähde: 
                <input type="text" 
                       value="Hanko syntyneiden ja kastettujen luettelo 1800-1806" 
                       size="60" name="source" /></p>
            <p>Lainaus (esim. sivunumero): 
                <input type="text" size="6" name="page" /></p>
            <p class="right"><input type="submit" value="Talleta" /></p>
        </form>
        </div>

        <?php
        /*
         * --- End of content page ---
         */
        include "inc/stop.php";
                        
