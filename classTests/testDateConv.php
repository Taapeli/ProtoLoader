<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>DateConv testi</title>
        <link rel="stylesheet" type="text/css" href="../style.css" />
    </head>

    <body>
        <?php
        /*
         * To change this license header, choose License Headers in Project Properties.
         * To change this template file, choose Tools | Templates
         * and open the template in the editor.
         */

        function __autoload($class_name) {
            // Polku on ilman '../', jos ei olla testihakemistossa
            include '../classes/' . $class_name . '.php';
        }
        
        echo "<h2>DateConv-testit</h2>\n";
        echo "<h3>fromGed</h3>\n";

        $geddates = [ "1 FEB 1900", "12 TOU 1901", "0 0 1913", "n. 1778",
            "1914", "FEB 2012", "Hauskaa pääsiäistä", "40 HEL 123" ];
        foreach ($geddates as $s) {
            echo "<p><b>" . DateConv::fromGed($s) . 
                    "</b> = DateConv::fromGed($s)</p>\n";
        }
        echo "<h3>toDisplay</h3>\n";
        $dates = [ "1900-02-01", "1901-05-12", "1913-00-00", "1914-08-00",
            "815-02-31", "1640-00-40", "1786.11.2", "1909.2.13", "1940.12.30"];
        foreach ($dates as $s) {
            echo "<p><b>" . DateConv::toDisplay($s) . 
                    "</b> = DateConv::toDisplay($s)</p>\n";
        }
        ?>
    </body></html>