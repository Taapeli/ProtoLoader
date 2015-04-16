<?php
include 'inc/checkUserid.php';
include "inc/start.php";
include 'libs/models/GedDateParser.php';
include "inc/dbconnect.php";

require_once 'libs/models/Individ.php';

$individs = Individ::findByLastname($sukudb, $userid, $_POST['name'], 0);

?>
