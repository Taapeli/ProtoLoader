<?php
include 'inc/checkUserid.php';
include "inc/start.php";
include 'libs/models/GedDateParser.php';
include "inc/dbconnect.php";

require_once 'libs/models/Individ.php';

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

    $individ_index = 0;

    foreach ($result as $rows)
    {
      $individ[$individ_index] = new Individ();
      $individ[$individ_index]->setId($rows[0]->getProperty('id'));
      $individ[$individ_index]->setFirstname($rows[1]->getProperty('first_name'));
      $individ[$individ_index]->setLastname($rows[1]->getProperty('last_name'));
      $individ[$individ_index]->setLaternames($rows[1]->getProperty('later_names'));
      $individ_index++;
    }


    for ($i=0; $i<sizeof($individ); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b) WHERE n.id='" .
        $individ[$i]->getId() . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $individ[$i]->setBirthdate($rows[0]->getProperty('birth_date'));
      }
    }

    for ($i=0; $i<sizeof($individ); $i++) {
      $query_string = "MATCH (n:Person:" . $userid . ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) WHERE n.id='" .
        $individ[$i]->getId() . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows)
      {
        $individ[$i]->setBirthplace($rows[0]->getProperty('name'));
      }
    }
  }
?>
