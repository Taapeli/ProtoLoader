<?php

/**
 * Neo4j-tietokannan avaaminen (ym. toimintoja?)
 * 13.2.2015 / JMä
 */
require('vendor/autoload.php');

class MyDatabase {

  /**
   * Neo4j-tietokannan avaaminen: 
   * $sukudb = MyDatabase::connect();
   * 
   * $todo Tätä ei ole testattu!
   */
  static function connect() {
    $pwFile = $_SERVER['DOCUMENT_ROOT'] . '/../dbinfo.dat';
    //echo " Tiedosto $pwFile\n";
    if (file_exists($pwFile)) {
      $fh = fopen($pwFile, 'r');
      $username = trim(fgets($fh));
      $password = trim(fgets($fh));
      $host = trim(fgets($fh));
      $port = trim(fgets($fh));
      fclose($fh);
    } else {
      die('No db password file');
    }
    //echo "connect $host:$port setAuth($username, ...)\n";

    $dbClient = new Everyman\Neo4j\Client($host, $port);
    $dbClient->getTransport()->setAuth($username, $password);
    // Jos käytettäisiin https:ää: ->useHttps()

    unset($username, $password, $host, $port);
    return $dbClient;
  }

}
