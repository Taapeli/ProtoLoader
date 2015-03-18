<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php /* php session_start(); */ ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - tiedoston lataus</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <!--
        Taapeli Project by Suomen Sukututkimusseura ry
        Creating a comprehensive genealogical database for Finland
        -->
    </head>
    <body>
        <?php
        //require '../inc/start.php';
        require_once '../libs/models/GedLoader.php';

        echo '<h1>Ladataan gedcom-tiedosto järjestelmään</h1>';

        /*
         * -------------------------- Tiedoston luku ----------------------------
         *
         * Simple file Upload system with PHP by Tech Stream
         * http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
         */

        if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
          // Tiedoston käsittelyn muuttujat
          $errors = array();
          $file_name = $_FILES['image']['name'];
          $file_size = $_FILES['image']['size'];
          $file_tmp = $_FILES['image']['tmp_name'];
          //$max_lines = $_POST["maxlines"];
          $x = explode('.', $file_name);
          $file_ext = strtolower(end($x));
          $expensions = array("ged", "degcom", "txt");

          if (!in_array($file_ext, $expensions)) {
            $errors[] = "Väärä tiedostopääte. Anna Gedcom -tiedosto, jonka pääte on "
                    . implode(', ', $expensions);
          } else {
            echo "<p><em>Ladattu tiedostiedosto " . $file_name
            . " (size=" . $file_size . ")</em><p>\n";

            /*
             * Process input file 
             */

            $loader = new GedLoader();
            $reply = $loader->loadFile($file_tmp);

            if (sizeof($reply) == 2) {
              // Got normal reply
              // Print error messages
              // ...
              // Print statistics
              $stat = $reply[1];
              echo "<p><em>{$file_name} {$stat[0]} riviä luettu, tallennettu:</em></p>";
              echo "<p><em>{$stat[2]} henkilöä <br />{$stat[3]} perhettä <br />"
              . "{$stat[4]} lähdettä <br />{$stat[5]} arkistoa</em></p>";
              echo "<p><em>Ohitettu {$stat[1]} riviä ei-kiinnostavia tageja</em></p>";
            }
          } // Good filename
        } // Filename given
        else {
          echo "<p>Tyhjä tiedostonimi, ei ladattu.</p>";
        } // No filename
        echo "</p>\n";


        //<!--End of content page -->

        require '../inc/stop.php';
        