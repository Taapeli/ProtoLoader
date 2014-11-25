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
<h1>Repo valikko Taapeli-kannasta</h1>
<div id="navigation">
<ul>
<?php

  require('vendor/autoload.php');

  $sukudb = new Everyman\Neo4j\Client('localhost', 7474);
 
  if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query_string = "MATCH (n:Repo) RETURN n ORDER BY n.name";
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
    $result = $query->getResultSet();

    foreach ($result as $rows)
    {
      $repo_id = $rows[0]->getProperty('id');
      $repo_name = $rows[0]->getProperty('name');
      echo "<li>
      <a href='addBirthRepo.php?id=$id&repo=$repo_id'>" . $repo_name . "</a>";
      echo "<ul>";
      $query_string2 = "MATCH (n:Repo)-[:REPO_SOURCE]->(s) WHERE n.name='" .
        $repo_name . "' RETURN s ORDER BY s.name";
      $query2 = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string2);
      $result2 = $query2->getResultSet();

      foreach ($result2 as $rows2)
      {
        $repo_source_id = $rows2[0]->getProperty('id');
        $repo_source = $rows2[0]->getProperty('name');
        echo "<li><a href='addBirthRepo.php?id=$id&repo=$repo_id&source=$repo_source_id'>" . $repo_source . "</a></li>";
      }
      echo "</ul>";
      echo "</li>";
    }
  }

?>

</body>
</html>
