<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Kirjautuminen ...</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>

        <?php
        session_start();

        if (isset($_POST['userid'])) {
          $_SESSION['userid'] = htmlentities($_POST['userid']);
          $_SESSION['taapeli'] = 'on';
        }
        include "../inc/start.php"; 

        // Content page starts here -->

        echo "<p>Käyttäjätunnus: " . $_SESSION['userid'] . " asetettu.</p>";
        echo "<div class='goback'><a href='/index.php'>Jatka</a></div>";
        ?>

        <!-- End of content page -->

        <?php include "../inc/stop.php"; ?> 

