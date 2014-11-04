<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kantaan</title>
<style>
b { color:red }
.form { background-color: #dde; margin-left: auto; margin-right: auto; }
th,td { padding: 5px; }
</style>
</head>

<body>
<div style="display: block; width: 100px; position: fixed;
    top: 1em; right: 1em; color: #FFF;
    background-color: #ddd;
    text-align: center; padding: 4px; text-decoration: none;"><a href="index.php">Paluu</a></div>
<h1>Taapeli testilataus</h1>
<p>Luetaan gedcom-tiedostoa.</p>
<?php

  require('vendor/autoload.php');

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

      function idtrim($id) {
        // Remove @ signs
        return substr(trim($id), 1, -1);
      }

      function monthtrim($month) {
        // Convert month from name to number
        switch ($month) {
          case "JAN":
            $month_num = "01";
            break;
          case "FEB":
            $month_num = "02";
            break;
          case "MAR":
            $month_num = "03";
            break;
          case "APR":
            $month_num = "04";
            break;
          case "MAY":
            $month_num = "05";
            break;
          case "JUN":
            $month_num = "06";
            break;
          case "JUL":
            $month_num = "07";
            break;
          case "AUG":
            $month_num = "08";
            break;
          case "SEP":
            $month_num = "09";
            break;
          case "OCT":
            $month_num = "10";
            break;
          case "NOV":
            $month_num = "11";
            break;
          case "DEC":
            $month_num = "12";
            break;
          default;
            $month_num = "00";
        }
        return $month_num;
      }

      $sukudb = new Everyman\Neo4j\Client('localhost', 7474);

      $idLabel = $sukudb->makeLabel('Person');
      $nameLabel = $sukudb->makeLabel('Name');
      $birthLabel = $sukudb->makeLabel('Birth');
      $deathLabel = $sukudb->makeLabel('Death');

      $n = 0;
      $load_individ = $load_family = false;

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

        if ($level == 0) {
          $id = idtrim($key);
          $id_1 = substr(trim($id), 0, 1);
          if ($id_1 == "I") {
            $load_individ = true;
            $load_family = false;
            $name_cnt = 0;
            $person[$id] = $sukudb->makeNode()
               ->setProperty('id', $id)
                  ->save();
            $idLabels = $person[$id]->addLabels(array($idLabel));
          }
          else if ($id_1 == "F") {
            $load_individ = false;
            $load_family = true;
          }
          // echo "id = " . $id . "\n";
        }
        else if ($level == 1) {
          if ($load_individ) {
            switch ($key)  {
              case "SEX":
                $person[$id]->setProperty('sex', $arg0)
                  ->save();
                break;
              case "NAME":
                $names = explode('/', $arg0, 3);
                if (sizeof($names)<2) {
                  $names[1] = "N";
                }

                if ($name_cnt++ == 0) {
                  $name = $sukudb->makeNode()
                    ->setProperty('first_name', $names[0])
                    ->setProperty('last_name', $names[1])
                    ->save();
                  $nameLabels = $name->addLabels(array($nameLabel));

                  $rel = $person[$id]->relateTo($name, 'HAS_NAME')->save();
                }
                else { // later names with another NAME tag
                  $later_name = $name
                    ->setProperty('later_name(s)', $names[1])
                  ->save();
                }
                break;
              case "ALIA":
                $names = idtrim($arg0);
                $alia = $name->setProperty('later_name(s)', $names)
                  ->save();
                break;
              case "BIRT":
                $event = "BIRT";
                $birt = $sukudb->makeNode()->save();
                $birthLabels = $birt->addLabels(array($birthLabel));
                $rel = $person[$id]->relateTo($birt, 'BIRTH')->save();
                break;
              case "DEAT":
                $event = "DEAT";
                $deat = $sukudb->makeNode()->save();
                $deathLabels = $deat->addLabels(array($deathLabel));
                $rel = $person[$id]->relateTo($deat, 'DEATH')->save();
                break;
              case "EMIG":
                $event = "EMIG";
                $emig = $sukudb->makeNode()->save();
                $rel = $person[$id]->relateTo($emig, 'MOVED_TO')->save();
                break;
              case "OCCU":
                $event = "OCCU";
                $occu_prev = $arg0; // CONC/CONT possible
                $occu = $sukudb->makeNode()
                  ->setProperty('occupation', $arg0)
                  ->save();
                $rel = $person[$id]->relateTo($occu, 'OCCUPATION')->save();
                break;
              case "NOTE":
                $event = "NOTE";
                $note_prev = $arg0; //  // CONC/CONT possible
                $note = $sukudb->makeNode()
                  ->setProperty('note', $arg0)
                  ->save();
                $rel = $person[$id]->relateTo($note, 'NOTE')->save();
                break;
              case "FAMC":
              case "FAMS":
                break;
              default;
                echo "Unknown tag on line: " . $n . "\n";
            } // switch $key
          }
          else if ($load_family) {
            switch ($key)  {
              case "HUSB":
                $husb = idtrim($arg0);
                break;
              case "WIFE":
                $wife = idtrim($arg0);
                $rel_married = $person[$husb]
                  ->relateTo($person[$wife], 'MARRIED')
                  ->save();
                break;
              case "CHIL":
                $chil = idtrim($arg0);
                $rel = $person[$husb]->relateTo($person[$chil], 'CHILD')->save();
                $rel = $person[$wife]->relateTo($person[$chil], 'CHILD')->save();
                $rel = $person[$chil]->relateTo($person[$husb], 'FATHER')->save();
                $rel = $person[$chil]->relateTo($person[$wife], 'MOTHER')->save();
                break;
              case "MARR":
                $event = "MARR";
                break;
              case "DIV":
                $event = "DIV";
                $rel_div = $person[$husb]->relateTo($person[$wife], 'DIVOCED')->save();
                break;
              case "NOTE":
                $event = "NOTE";
                $note_prev = $arg0; // CONC/CONT possible
                $note = $sukudb->makeNode()
                  ->setProperty('note', $arg0)
                  ->save();
                $rel_husb = $person[$husb]->relateTo($note, 'NOTE')->save();
                $rel_wife = $person[$wife]->relateTo($note, 'NOTE')->save();
                break;
              default;
                echo "Unknown tag on line: " . $n . "\n";
            } // switch $key
          }
        }
        else if ($level == 2) {
          if ($load_individ) {
            switch ($key)  {
              case "NSFX":
                $nsfx = $name->setProperty('partonymic', $arg0)->save();
                break;
              case "DATE":
                $date = explode(' ', $arg0, 3);
                if (sizeof($date) == 3) {
                  $date[1] = monthtrim($date[1]);
                  $date_str = $date[2] . "." . $date[1] . "." . $date[0];
                }
                switch ($event) {
                  case "BIRT":
                    $birt_date = $birt
                      ->setProperty('birth_date', $date_str)
                      ->save();
                    break;
                  case "DEAT":
                    $deat_date = $deat
                      ->setProperty('death_date', $date_str)
                      ->save();
                    break;
                  default;
                    echo "Unknown tag on line: " . $n . "\n";
                } // $event
                break;
              case "PLAC":
                switch ($event) {
                  case "BIRT":
                    $birt_plac = $birt
                      ->setProperty('birth_place', $arg0)
                      ->save();
                    break;
                  case "DEAT":
                    $deat_plac = $deat
                      ->setProperty('death_place', $arg0)
                      ->save();
                    break;
                  case "EMIG":
                    $emig_plac = $emig
                      ->setProperty('moved_to', $arg0)
                      ->save();
                    break;
                  default;
                    echo "Unknown tag " . $key . " on line: " . $n . "\n";
                } // $event
                break;
              case "CONC":
                switch ($event) {
                  case "OCCU":
                    $occu_prev = $occu_prev . " " . $arg0;
                    $occu_conc = $occu
                      ->setProperty('occupation', $occu_prev)
                      ->save();
                  break;
                  case "NOTE":
                    $note_prev = $note_prev . " " . $arg0;
                    $note_conc = $note
                      ->setProperty('note', $note_prev)
                      ->save();
                   break;
                  default;
                   echo "Unknown tag " . $key . " on line: " . $n . "\n";
                } // $event
                break;
              case "CONT":
                switch ($event) {
                  case "NOTE":
                    $note_prev = $note_prev . " " . $arg0;
                    $note_cont = $note
                      ->setProperty('note', $note_prev)
                      ->save();
                    break;
                  default;
                    echo "Unknown tag " . $key . " on line: " . $n . "\n";
                } // $event
                break;
              default;
                echo "Unknown tag " . $key . " on line: " . $n . "\n";
            } // switch $key
          }
          else if ($load_family) {
            switch ($key)  {
              case "DATE":
                $date = explode(' ', $arg0, 3);
                if (sizeof($date) == 3) {
                  $date[1] = monthtrim($date[1]);
                  $date_str = $date[2] . "." . $date[1] . "." . $date[0];
                }
                switch ($event) {
                  case "MARR":
                    if (sizeof($date) == 3) {
                      $marr_date = $rel_married
                        ->setProperty('married_date', $date_str)
                        ->save();
                    }
                    else {
                      $marr = $rel_married
                        ->setProperty('married_status', $arg0)
                        ->save();
                    }
                    break;
                  case "DIV":
                    if (sizeof($date) == 3) {
                      $div_date = $rel_div
                        ->setProperty('divoced_date', $date_str)
                        ->save();
                    }
                    else {
                      $marr = $rel_div
                        ->setProperty('divoced_status', $arg0)
                        ->save();
                    }
                    break;
                  default;
                    echo "Unknown tag " . $key . " on line: " . $n . "\n";
                } // $event
                break;
              case "PLAC":
                switch ($event) {
                  case "MARR":
                    $marr = $rel_married
                      ->setProperty('married_place', $arg0)
                      ->save();
                    break;
                  default;
                    echo "Unknown tag " . $key . " on line: " . $n . "\n";
                } // $event
                break;
              default;
                echo "Unknown tag on line: " . $n . "\n";
            } // $key
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
<!-- 

<form action="" method="POST" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<h2>Anna ladattava gedcom-tiedosto</h2>
<p>Sy&ouml;te: <input type="file" name="image" required/></p>
<p>Merkist&ouml;: <input type="radio" name="charset" value="UTF-8" checked>UTF-8
   (<input type="radio" name="charset" value="UTF-16" disabled>UTF-16LE ei tarjolla)
</p>
<p><input type="checkbox" name="show" value="ged" checked>N&auml;yt&auml; my&ouml;s gedcom-tietokent&auml;t</p>
<p>K&auml;sitelt&auml;v&auml; maksimi rivim&auml;&auml;r&auml;
   <input type="number" name="maxlines" value="999"></p>
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>
-->
</body>
</html>
