<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli aineiston luku kantaan</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Taapeli testilataus</h1>
<p>Luetaan gedcom-tiedostoa.</p>
<?php

  include "inc/dbconnect.php";

/*-------------------------- Tiedoston luku ----------------------------*/
/*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
    // Tiedoston käsittelyn muuttujat
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $max_lines = $_POST["maxlines"];
    $x=explode('.',$file_name);
    $x=end($x);
    $file_ext = strtolower($x);	
    $expensions= array("ged","degcom","txt"); 

    if(in_array($file_ext,$expensions)=== false){
      $errors[]="Väärä tiedostopääte. Anna Gedcom -tiedosto, jonka pääte on " .
			"ged, degcom tai txt";
    }

    if($file_size > 2097152){
      $errors[].='Tiedostokoko on nyt rajoitettu 2 Mb:een ';
    }

    if(empty($errors)==true) {
      echo "<p><em>Ladattu ty&ouml;tiedosto: " . $file_tmp 
	. " (size=" . $file_size . ") <-- " . $file_name;
	// . ", charset=" . $_POST["charset"]
	// . ", k&auml;sitell&auml;&auml;n enint&auml;&auml;n " . $max_lines
	// . " rivi&auml;";
	//	
	// if ($_POST["show"] == 'ged') {
	//   $sg = true;		// Näytetään ged-koodi
	// }
      echo "</em><p>\n";

      

      $placeLabel = $sukudb->makeLabel('Place');

      $n = 0;

/*
** Avataan tiedosto
*/
      $file_handle = fopen($file_tmp, "r");
      while (!feof($file_handle)) {
        $line = fgets($file_handle);
        $n++;
        $a = explode(' ', $line, 3);
        $level = $a[0];

        if (sizeof($a) > 1) {
          $key = trim($a[1]);
        }
        else {
          // echo "\n\nOnly one argument on line: " . $n . "\n";
          $key = "";
        }

        if (sizeof($a) > 2) {
          $arg = $arg0 = trim($a[2]);
        }

        if ($level == 2) {
          switch ($key)  {
            case "PLAC":
              $query_string = "MERGE (n:Place {name:'" . $arg0 . "'})";

              $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

              $result = $query->getResultSet();
              break;
            default;
          }
        }
      } // while feof
    }
    echo "</p>\n";
			
    fclose($file_handle);
    echo "<p><em>{$file_name} {$n} rivi&auml;</em></p>";
  }
  else {
    print_r($errors);
  }

/*-------------------------- Tiedoston valintalomake ----------------------------*/
?>

<form action="" method="POST" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<h2>Anna ladattava paikka-tiedosto</h2>
<p>Sy&ouml;te: <input type="file" name="image" required/></p>
<!-- 
<p>Merkist&ouml;: <input type="radio" name="charset" value="UTF-8" checked>UTF-8
   (<input type="radio" name="charset" value="UTF-16" disabled>UTF-16LE ei tarjolla)
</p>
<p><input type="checkbox" name="show" value="ged" checked>N&auml;yt&auml; my&ouml;s gedcom-tietokent&auml;t</p>
<p>K&auml;sitelt&auml;v&auml; maksimi rivim&auml;&auml;r&auml;
   <input type="number" name="maxlines" value="999"></p>
-->
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>
</body>
</html>
