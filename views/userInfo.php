<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php /* php session_start(); */ ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelin käyttäjät</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <!--
        Taapeli Project by Suomen Sukututkimusseura ry
        Creating a comprehensive genealogical database for Finland
         -->
         <style type="text/css">
           .right { text-align: right; }
         </style>
    </head>
    <body>
        <?php
        //require '../inc/start.php';
        require_once '../libs/models/User.php';
        
        if (!empty($_GET['user'])) {
          $user = filter_input(INPUT_GET, 'user');
        }

        $me = User::getUser($user);
        echo '<!--' . $me->dump() . '-->';
        $stats = $me->getStats();
        echo '<br />';
        //var_dump($stats);
        ?>

        <h1>Käyttäjän <i><?php echo $me->getUserid(); ?></i> lataamat tiedot</h1>
        <!--<p>Yhteensä <?php echo $stats['NODE:PERSON']; ?> henkilöä</p>  -->
        <table class="tulos">
            <tr><th>Kohteet</th><th>lukumäärä</th></tr>
                <?php
                foreach ($stats as $key => $s):
                  // if (strncmp($key, 'NODE', 4) != 0) {
                    echo "<tr><td>" . ucwords(strtolower($key)) 
                            . "</td><td class='right'>" . $s . "</td></tr>";
                  //}
                endforeach;
                ?>
        </table>

        <!-- End of content page -->

<?php require '../inc/stop.php'; ?> 
