<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelin </title>
        <meta http-equiv="refresh" content="3; url=../index.php" />
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <?php
        session_destroy();
        include "start.php"; 
        ?>
        <p>Istuntosi on päättynyt, palaamme kohta alkusivulle</p>
    </body></html>
