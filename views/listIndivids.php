<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelista haku</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
        <?php
        include 'inc/start.php';

        echo "<h1>Haku nimellä <i>$input_name</i> </h1>";

        echo '<table class="tulos">';
        echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
        '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';

        foreach ($individs as $i => $indi) {
          echo "<tr><td><a href='readIndividData.php?id=" . $indi->getId() . "'>"
          . $indi->getId() . "</a></td>";
          echo "<td> " . $indi->getFirstname() .
          "</td><td> " . $indi->getLastname() .
          "</td><td> " . $indi->getLaternames() .
          "</td><td> " . GedDateParser::toDisplay($indi->getBirthdate()) .
          "</td><td> " . $indi->getBirthplace() .
          "</td></tr>";
        }
        echo "</table><p>&nbsp;</p>";
        
        include 'inc/stop.php';
