<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- @todo ohjelma, jolle toiminnot lähetetään -->
        <title>Taapelista haku</title>
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

        $query_string = "MATCH (u:Userid) RETURN u ORDER BY u.userid";

        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
        $result = $query->getResultSet();

        foreach ($result as $rows) {
          $user[] = $rows[0]->getProperty('userid');
        }
        ?>
        <form action="">
            <h1>Käyttäjätunnukset Taapeli-kannassa</h1>
            <table class="tulos">
                <tr><th>Userid</th><th>Valinta</th></tr>
                <?php
                for ($i = 0; $i < sizeof($user); $i++) {
                  echo "<tr><td><a href='showContent.php?user=" 
                          . $user[$i] . "'>" . $user[$i] . "</a></td>"
                    . "<td><span style='align:center;'>"
                          . "<input type='radio' name='userid' value=" 
                          . $user[$i] . "></span></td></tr>";
                }
                ?>
            </table>
            <h2>Toiminnot</h2>
            <p>Yhdistä valitun käyttäjän saman syntymäajan omaavat
                <input type='submit' value='Tämä painike ei vielä käytössä' /></p>
            <p>Yhdistä valitun käyttäjän saman etu- ja sukunimen omaavat
                <input type='submit' value='Tämä painike ei vielä käytössä' /></p>
            <p>Valittu käyttäjä edustamaan Taapeli-kantaa
                <input type='submit' value='Tämä painike ei vielä käytössä' /></p>
        </form><p>&nbsp;</p>
        <?php
        /*
         * --- End of content page ---
         */
        include "inc/stop.php";
        