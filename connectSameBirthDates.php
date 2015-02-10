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
<h1>Kytket&auml;&auml;n kaikki saman syntym&auml;p&auml;iv&auml;n omaavat</h1>
<?php

  require('vendor/autoload.php');

    include("openSukudb.php");

    $query_string = "MATCH (n:Person:user0498), (m:Person:user6321) WHERE m.birth_date = n.birth_date RETURN n.id, m.id";

    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $id[] = $rows[0];
      $id2[] = $rows[1];
    }

    $may_be_same = 0;
    for ($i=0; $i<sizeof($id); $i++) {
      $may_be_same++;
      $query_string = "MATCH (n:Person:user0498) WHERE n.id='" .
        $id[$i] . "' MATCH (m:Person:user6321) WHERE m.id='" .
        $id2[$i] . "' MERGE (n)-[r:MAY_BE_SAME]-(m) SET r.indication1 = 1";

      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();
    }
    echo "May be the same connected: " . $may_be_same . "\n";

?>

</body>
</html>
