<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Avataan tietokanta</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<h1>Noe4j-kannan avaaminen</h1>
<p>Avataan valittu palvelin ja portti ...</p>
<pre>
<?php
	  require('vendor/autoload.php');
	  
	  use everyman\Neo4j\Client;
	  $client = new Everyman\Neo4j\Client('127.0.0.1', 1337);

	  print_r($client->getServerInfo()); 
?>
</pre>
<p>... avattu!</p>
<p>Lähde: <a href="http://stackoverflow.com/questions/26576871/neo4jphp-cannot-instantiate-abstract-class-everyman-neo4j-transport">Stackoverflow</p>
</body>
</html>
