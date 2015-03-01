<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
        <?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli - samannimiset ehdokkaat</title>
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

    echo '<h1>Kytket&auml;&auml;n kaikki saman nimen omaavat</h1>';

    $query_string = "MATCH (n:Person:user0498)-[:HAS_NAME]-(a), "
            . "(m:Person:user6321)-[:HAS_NAME]-(b) "
            . "WHERE ((a.first_name = b.first_name) AND (a.last_name = b.last_name)) "
            . "RETURN n.id, m.id";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();
    $id = $id2 = [];

    foreach ($result as $rows)
    {
      $id[] = $rows[0];
      $id2[] = $rows[1];
    }

    $may_be_same = 0;
    for ($i=0; $i<sizeof($id); $i++) {
      $may_be_same++;
      $query_string = "MATCH (n:Person:user0498) WHERE n.id='" .
        $id[$i] . "' MATCH (m:Person:user6321) WHERE m.id='" .
        $id2[$i] . "' MERGE (n)-[r:MAY_BE_SAME]-(m) SET r.indication2 = 1";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();
    }
    echo "<p>May be the same connected: " . $may_be_same . "</p>\n";

  /*
   * --- End of content page ---
   */
include "inc/stop.php";
