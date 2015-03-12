<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>DateConv testi</title>
        <link rel="stylesheet" type="text/css" href="../style.css" />
        <style type="text/css">
            body { background-color: aliceblue;
                   background-image: url("../images/Vaakuna_65px.png");
                   background-repeat: no-repeat;
            }
            h1,h2,h3 { margin-left: 65px; }
            .old { color: gray; }
            i { color: darkorange; font-style: normal; }
            b {color: saddlebrown; background-color: lightgray; }
            .error { color:red; }
            div.inline {
                display:inline; background-color: white;
                width:48%;
                border: 1px solid gray;	
                float:left;
                padding: 4px; margin: 2px;
            }
        </style>
    </head>

    <body>
        <?php
        /*
         * Taapeli Project by Suomen Sukututkimusseura ry
         * Creating a comprehensive genealogical database for Finland
         */
        $geddates = [ '1 FEB 1900', '11 MAR 1640', '12 JOU 1901', '0 0 1913',
            'n. 1778', '1914', 'FEB 2012', 'Pääsiäisenä 1913', '40 HEL 1234',
            '1830 syksyllä', '5 päivää myöhemmin', '8 MAY 123', 'EST 12 OCT 1430',
            'ABT 21 JAN 1640', 'CAL 22 FEB 1641', 'EST 23 MAY 1642',
            'FROM 1914 TO 1920', 'FROM 12 APR 1720 TO 15 JUN 1721', 
            'BEF SEP 1900', 'AFT 1820', 'AFT 5 DEC 1917',
            'BETW 24 JUN 1812 AND 31 AUG 1812',
        ];
        $dates = ["1900-02-01", "1913-00-00", "1914-08-00",
            "815-02-31", "1640-00-40", "1786.11.2", "1909.2.13", "1940.12.30"];

/* Tästä ei ole vielä tullut tolkkua ...
        function __autoload($class_name) {
          // Polku on ilman '../', jos ei olla testihakemistossa
            include '../classes/' . $class_name . '.php';
        }
*/
        define('MAINDIR', __DIR__ . '/../');
        require_once(MAINDIR . 'libs/models/GedDateParser.php');
      //require_once(MAINDIR . 'classes/DateConv.php');

        echo "<h2>Päivämäärämuunnoksen <i>GedDateParser</i> testit</h2>
          <p>Toimii molempiin suuntiin</p>
        <div>
        <div class='inline'>
        <h3>fromGed tietokantaan</h3>\n";

        $ged = new GedDateParser();
        foreach ($geddates as $s) {
          try {
            $dgdate = $ged->fromGed($s);
            echo "<p><b>$dgdate</b> = \$myGedDateParser->fromGed(<i>$s</i>)<br />\n";
            $dates[] = $dgdate; // Show also toDisplay
          } catch (Exception $e) {
            echo "<p><em class='error'>Error gedDateParser: " . $e->getMessage() .
                "</em> = GedDateParser->fromGed(<i>$s</i>).<br />\n";
          }
        //echo "<span class='old'><b>" . DateConv::fromGed($s) .
        //"</b> = DateConv::fromGed(<i>$s</i>)</span></p>\n";
        }
        echo "</div>
        <div class='inline'>
        <h3>toDisplay tietokannasta</h3>\n";

        foreach ($dates as $s) {
        echo "<p><b>" . GedDateParser::toDisplay($s) .
        "</b> = GedDateParser::toDisplay(<i>$s</i>)</p>\n";
        }
        ?>
        </div>
      </div>
    </body></html>