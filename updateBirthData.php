<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli aineiston ylläpito</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div class="goback">
            <a href="index.php">Paluu</a></div>
            <h1>Taapeli testiylläpito</h1>
            <p>Syntymätiedon muokkaus</p>
<?php
        include 'classes/DateConv.php';
        include "inc/dbconnect.php";

        if (!isset($_GET['id'])) {
          echo '<p>Ei valittua henkilöä</p></body></html>';
          return;
        }
        // Tiedoston käsittelyn muuttujat
        $input_id = $_GET['id'];

        // Neo4j parameter {id} is used to avoid hacking injection
        $query_string = "MATCH (n:Person) WHERE n.id={id} RETURN n";
        $query_array = array('id' => $input_id);
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
        $result = $query->getResultSet();

        foreach ($result as $rows) {
          $id = $rows[0]->getProperty('id'); // This variable is used for later MATCHs
        }

        $query_string = "MATCH (n:Person)-[:BIRTH]->(b) WHERE n.id='" . $id 
                . "' RETURN b";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows) {
          $birth_date = $rows[0]->getProperty('birth_date');
        }

        $query_string = "MATCH (n:Person)-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) "
                . "WHERE n.id='" . $id . "' RETURN p";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows) {
          $birth_place = $rows[0]->getProperty('name');
        }

        $query_string = "MATCH (n:Person)-[:HAS_NAME]-(m) "
                . "WHERE n.id='" . $id . "' RETURN m";
        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

        $result = $query->getResultSet();

        foreach ($result as $rows) {
          $first_name = $rows[0]->getProperty('first_name');
          $last_name = $rows[0]->getProperty('last_name');
          $later_names = $rows[0]->getProperty('later_names');
        }
?>  
        <form action="changeBirthData.php" method="post" enctype="multipart/form-data">
            <table  class="tulos">
                <tr><th>id</th><th>Etunimet</th><th>Sukunimi</th><th>Myöh. sukunimi</th>
                    <th>Syntymäaika</th><th>Syntymäpaikka</th></tr>
<?php
        echo "<tr><td rowspan='2'>$id</td><td>$first_name</td><td>$last_name</td>";
        echo "<td>$later_names</td><td>" . DateConv::toDisplay($birth_date) 
                . "</td><td>$birth_place</td></tr>";
        echo '<tr><td colspan="3"><div  class="right">Uudet tiedot:</div></td>';
        echo '<td><input type="text" name="birth" value="' .$birth_date. '" /></td>';
        echo '<td><input type="text" name="place" value="' . $birth_place . '" /></td></tr>';
?>
                <tr>
                    <td colspan="6" >
                        <div class="right">
                            <a href="#" onclick="history.go(-1)">Peru</a>
                            <input type="submit" value="Talleta"/>
                        </div>
                    </td>
                </tr>
            </table>
        </form>

    </body>
</html>
