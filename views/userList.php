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
    </head>
    <body>
        <?php
        //require '../inc/start.php';
        require_once '../libs/models/User.php';
        $users = User::getAllUsers();
        echo '<br />';
        //var_dump($users);
        ?>

        <!-- Content page starts here -->

        <h1>Taapelin käyttäjät</h1>
        <p>Yhteensä <?php echo count($users); ?> käyttäjää</p>  
        <table class="tulos">
            <tr><th>Käyttäjä</th><th>Rooli</th></tr>
                <?php
                foreach ($users as $user):
                  require 'showUser.php';
                endforeach;
                ?>
        </table>

        <!-- End of content page -->

<?php require '../inc/stop.php'; ?> 
