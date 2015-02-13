<?
// Neo4j-tietokannan avaaminen
//
// 13.2.2015 / JMä

	require('vendor/autoload.php');	  
	use Everyman\Neo4j\Client;

	$pwFile = $_SERVER['DOCUMENT_ROOT'] . '/../dbinfo.dat';
	//echo " Tiedosto $pwFile\n";
	if (file_exists($pwFile)) { 
	   $fh = fopen($pwFile, 'r');
	   $username = trim(fgets($fh));
	   $password = trim(fgets($fh));
	   $host = trim(fgets($fh));
	   $port = trim(fgets($fh));
	   fclose($fh);
	} else die("No password file");
	//echo "connect $host:$port setAuth($username, ...)\n";

	$sukudb = new Everyman\Neo4j\Client($host, $port);
	$sukudb->getTransport()
	  ->setAuth($username, $password);
	  // Ei käytetä https:ää ->useHttps()
	
	unset($username, $password, $host, $port);
?>
