<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelin prototyypin etusivu</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <?php include "inc/start.php"; ?>

        <!-- Content page starts here -->

        <h1>Taapeli-projekti</h1>
        <p>
            Projektissa suunnitellaan ja toteutetaan keskitetty tietojärjestelmä, 
            johon sukututkijat voivat helposti ladata lähtein 
            varmennettuja sukututkimustietoja. </p>
        <p>  Järjestelmään tehdään selainkäyttöliittymät tietojen lataamiseen, 
            validointiin ja selaamiseen. </p>

        <h2>Kirjaudu järjestelmään</h2>
        <p>Taapelin sovelluksia saavat käyttää vain siihen kirjautuneet käyttäjät.</p>
        <p><a href="http://taapeli.referata.com/wiki/Ohjeet" target="help">Ohjeet</a></p>
        
        <h2>Teknisiä kokeita</h2>
        <p><i><a href="http://advancedkittenry.github.io/koodaaminen/arkkitehtuuri/index.html"
               target="_blank">MVC-mallin</a></i> mukaiset haut on merkitty 
        <img src="images/New_icons_21.gif" alt="uusi" />-kuvakkeella</p>
        <p>Arkkitehtuurikokeiluja MVC-mallin mukaisesti:
            <a href="views/userList.php">Käyttäjäluettelon haku</a> (simuloitu aineisto).
        </p>
        <p>&nbsp;</p>
    
    <!-- End of content page -->

        <?php include "inc/stop.php"; ?> 
