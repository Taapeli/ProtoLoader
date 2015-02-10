<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli haku</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Irroitetaan kaikki saman nimen omaavat</h1>
<?php

  require('vendor/autoload.php');

    include("openSukudb.php");

    $query_string = "MATCH (n:Person:user0498)-[r:MAY_BE_SAME]-() WHERE r.indication2 = 1 SET r.indication2 = 0 RETURN count(r)";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $removed = $rows[0];
    }

    echo "Number of disconnections: " . $removed . "\n";

?>

</body>
</html>
