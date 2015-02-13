<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Avataan tietokanta</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<h1>Neo4j-kannan avaaminen</h1>
<p>Avataan valittu palvelin ja portti ...</p>
<pre>
<?php
	require('vendor/autoload.php');	  
	use Everyman\Neo4j\Client;
	$pwFile = $_SERVER['DOCUMENT_ROOT'] . '/../keys/dbinfo.dat';
	echo " Tiedosto $pwFile\n";
	if (file_exists($pwFile)) { 
	   $fh = fopen($pwFile, 'r');
	   $username = trim(fgets($fh));
	   $password = trim(fgets($fh));
	   $host = trim(fgets($fh));
	   $port = trim(fgets($fh));
	   fclose($fh);
	} else die("No password file");
	echo "connect $host:$port setAuth($username, ...)\n";

	$client = new Everyman\Neo4j\Client($host, $port);
	$client->getTransport()
	  ->setAuth($username, $password);
	  // Ei käytetä https:ää ->useHttps()
	
	print_r($client->getServerInfo()); 
?>
</pre>
<p>... avattu!</p>
<p>Lähde: <a href="http://stackoverflow.com/questions/26576871/neo4jphp-cannot-instantiate-abstract-class-everyman-neo4j-transport">Stackoverflow</p>
</body>
</html>
