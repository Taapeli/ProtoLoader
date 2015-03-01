
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelista haku</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
<?php
include 'inc/checkUserid.php';
include "inc/start.php";
include 'classes/DateConv.php';
include "inc/dbconnect.php";

        /*
         * -- Content page starts here -->
         */

if(isset($_POST['name']) || isset($_POST['wildcard'])){
    // Check search variables
    if (isset($_POST['wildcard']) && ($_POST['wildcard'] != '')) {
      $use_wildcard = true;
      // Protecting against exploits
      $input_wildcard = htmlspecialchars($_POST['wildcard']);
      echo "<h1>Haku nimen alkuosalla <i>${input_wildcard}...</i></h1>";
      $input_wildcard .= ".*";
    } else {
      $use_wildcard = false;
      $input_name = htmlspecialchars($_POST['name']);
      echo "<h1>Haku nimellä <i>$input_name</i> </h1>";
    }

    //echo "<p>Poimittu nimellä = '$input_name''$input_wildcard'</p>";

    if (! $use_wildcard) {
      // Neo4j parameter {name} is used to avoid hacking injection
      $query_string = "MATCH (n:Name:" . $userid . ")<-[:HAS_NAME]-(id:Person:" . $userid . ") " .
              "WHERE n.last_name={name} OR n.later_names={name} "
              . "RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('name' => $input_name);
    }
    else {
      // Neo4j parameter {wildcard} is used to avoid hacking injection
      $query_string = "MATCH (n:Name:" . $userid . ")<-[:HAS_NAME]-(id:Person:" . $userid . ") " .
              "WHERE n.last_name=~{wildcard} OR n.later_names=~{wildcard} "
              . "RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('wildcard' => $input_wildcard);
    }
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();
    $id = [];
    $first_name = [];

    foreach ($result as $rows)
    {
      $id[] = $rows[0]->getProperty('id');
      $first_name[] = $rows[1]->getProperty('first_name');
      $last_name[] = $rows[1]->getProperty('last_name');
      $later_names[] = $rows[1]->getProperty('later_names');
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b) WHERE n.id='" .
        $id[$i] . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $birth_date[$i] = $rows[0]->getProperty('birth_date');
      }
    }

    for ($i=0; $i<sizeof($id); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" .
        $id[$i] . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $birth_place[$i] = $rows[0]->getProperty('name');
      }
    }
  }

  echo '<table class="tulos">';
  echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
       '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  for ($i=0; $i<sizeof($first_name); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" . $id[$i] . "'>" 
         . $id[$i] . "</a></td>";
    echo "<td> " . (isset($first_name[$i]) ? $first_name[$i] : '-') .
         "</td><td> " . (isset($last_name[$i]) ? $last_name[$i] : '-') .
         "</td><td> " . (isset($later_names[$i]) ? $later_names[$i] : '-') .
         "</td><td> " . (isset($birth_date[$i]) ? DateConv::toDisplay($birth_date[$i]) : '-') .
         "</td><td> " . (isset($birth_place[$i]) ? $birth_place[$i] : '-') .
         "</td></tr>";
  }
  echo "</table><p>&nbsp;</p>";
  /*
   * --- End of content page ---
   */
include "inc/stop.php";
