<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelin prototyypin etusivu</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <style type="text/css">
            div.form { background-color: #dde; 
                       width: 25em;
                       border: 2pt outset gray;
                       padding: 6pt;
            }
        </style>
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
<!--
        <form action="inc/setUserid.php" method="post" enctype="multipart/form-data">
            <?php /*
            if (isset($_SESSION['userid'])) {
              echo "<p>Käyttäjä " . $_SESSION['userid'] 
                      . "<br /><input type='submit' name='button' value='Kirjaudu ulos' /></p>";
            } else {
           */   ?>
              <div class="form">
                  <h2>Kirjaudu Taapeliin</h2>
                  <p>Käyttäjätunnus 
                      <input type="text" name="userid" />
                      <input type="submit" name="button" value="Kirjaudu"  /></p>
                  <p>Ei käyttäjätunnusta? Rekisteröidy käyttäjäksi
                      <a href="#" onclick="alert('Ilmoittaudu vaikka Jormalle')">tästä</a></p>
              </div>
              <?php
        /*  } */
            ?>
        </form>
-->
        <!-- End of content page -->

        <?php include "inc/stop.php"; ?> 
