<?php
/**
 * Controller for search by name
 */
session_start();
include 'inc/checkUserid.php';
include 'inc/dbconnect.php';
include 'libs/models/GedDateParser.php';
include 'libs/models/Individ.php';

$input_name = null;
$method = 0; // Full name
if (!empty($_POST['name'])) {
  $input_name = htmlspecialchars($_POST['name']);
}
if (isset($_POST['method'])) {
  $method = 1; // match from beginning
}

$individs = Individ::findByLastname($sukudb, $userid, $input_name, $method);

include 'views/listIndivids.php';
