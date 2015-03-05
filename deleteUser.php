<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - tietojen poisto</title>
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

  echo '<h1>Tiedot poistettu</h1>';

  if (!isset($_GET['user'])) {
    echo '<p>Ei valittua henkilöä</p></body></html>';
    die;
  }

  // Tiedoston käsittelyn muuttujat
  $user = $_GET['user'];

  $query_string = "MATCH (n:" . $user . ")-[r]-() DELETE n,r";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  $query_string = "MATCH (u:Userid) WHERE u.userid = '" . $user . "' DELETE u";

  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
  $result = $query->getResultSet();

  /*
   *  -- End of content page -->
   */

include "inc/stop.php";
