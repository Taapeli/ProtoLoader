<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - yhdistely syntymäajalla</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
        <?php
        include 'inc/checkUserid.php';
        include 'inc/start.php';
        include 'inc/dbconnect.php';

        /*
         * -- Content page starts here -->
         */

        echo "<h1>Kytketään kaikki saman syntymäpäivän omaavat";

        if (isset($_POST['userid'])) {
          $user2 = htmlentities($_POST['userid']);
        }
        if (!isset($user2)) {
          echo "</h1><p>Yhdistelyä varten tarvitaan toinen käyttäjätunnus, "
          . "jonka aineistoon verrataan</p>";
        } else {
          echo "(<i>$user&lt;&gt;$user2</i></h1>";
          $query_string = "MATCH (n:Person:$user), (m:Person:$user2) "
                  . "WHERE m.birth_date = n.birth_date RETURN n.id, m.id";
          $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
          $result = $query->getResultSet();
          $id = [];

          foreach ($result as $rows) {
            $id[] = $rows[0];
            $id2[] = $rows[1];
          }

          $may_be_same = 0;
          for ($i = 0; $i < sizeof($id); $i++) {
            $may_be_same++;
            $query_string = "MATCH (n:Person:$user) WHERE n.id='" . $id[$i]
                    . "' MATCH (m:Person:$user2) WHERE m.id='" . $id2[$i]
                    . "' MERGE (n)-[r:MAY_BE_SAME]-(m) SET r.indication1 = 1";

            $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
            $result = $query->getResultSet();
          }
          echo "<p>Mahdolliset samat, " . $may_be_same . " yhdistetty</p>\n";
        }
        /*
         * --- End of content page ---
         */
        include "inc/stop.php";
        