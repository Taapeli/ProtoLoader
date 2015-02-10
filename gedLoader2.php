<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kantaan</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
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

      function nametrim($id) {
        // Remove / signs if exist
        $id_1 = substr(trim($id), 0, 1);
        if ($id_1 == "/") {
          return substr(trim($id), 1, -1);
        }
        else {
          return $id;
        }
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

      include("openSukudb.php");

      $idLabel = $sukudb->makeLabel('Person');
      $nameLabel = $sukudb->makeLabel('Name');
      $marriageLabel = $sukudb->makeLabel('Marriage');
      $sourceLabel = $sukudb->makeLabel('Source');
      $repoLabel = $sukudb->makeLabel('Repo');

      $n = 0;
      $n = $n_indi = $n_fam = $n_sour = $n_repo = 0; // How many lines were read
      $load_type = ""; // values: INDI, FAM, SOUR, REPO 

/*
** Avataan tiedosto
*/
      $file_handle = fopen($file_tmp, "r");
  while (!feof($file_handle)) {
    $n++;
    $line = fgets($file_handle);
    $a = explode(' ', $line, 3);
    $level = $a[0];

    if (sizeof($a) > 1) {
      $key = trim($a[1]);
    }
    else {
      echo "\n\nOnly one argument on line: " . $n . "\n";
      $key = "";
    }

    if (sizeof($a) > 2) {
      $arg = $arg0 = trim($a[2]);
    }

        if ($level == 0) {
          $id = idtrim($key);
          if (sizeof($a) > 2) {
            switch ($arg0) {
              case "INDI":
                if (!$phon_found) {
                  echo "Tiedostoa ei voitu tallentaa tietokantaan gedcom-tiedostosta puuttuvan puhelinnumeron vuoksi!\n\n";
                  echo "Lisää puhelinnumero käyttäjätietoihin, esim. 1 PHON 040 123 4567\n";
                  exit;
                }
                $n_indi++;
                $load_type = "INDI";
                $name_cnt = 0;
                $query_string = "CREATE (n:Person {id:'" . $id . 
                        "'})";

                $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                $result = $query->getResultSet();

                break;
              case "FAM":
                $n_fam++;
                $load_type = "FAM";
                $fam_id = $id;
                $query_string = "CREATE (n:Marriage {id:'" . $fam_id . "'})";

                $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                $result = $query->getResultSet();
                break;
              case "SOUR":
                $n_sour++;
                $load_type = "SOUR";
                $sour_id = $id;
                break;
              case "REPO":
                $n_repo++;
                $load_type = "REPO";
                $repo_id = $id;
                break;
              case "SUBM":
                $load_type = "SUBM";
                break;
              default;
                echo "Unknown tag " . $arg0 . " on line: " . $n . "\n";
                $event = "";
                $load_type = "";
            }
          }
          // echo "id = " . $id . "\n";
        }
        else if ($level == 1) {
          switch ($load_type) {
            case "INDI":
              switch ($key)  {
                case "SEX":
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                          "'}) SET n.sex ='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "NAME":
                  $names = explode('/', $arg0, 3);
                  if (sizeof($names)<2) {
                    $names[1] = "N";
                  }
  
                  if ($name_cnt++ == 0) {
                    $query_string = "MATCH (n:Person {id:'" . $id . 
                          "'}) CREATE (m:Name {first_name:'" . $names[0] . 
                          "', last_name:'" . $names[1] . "'}) MERGE (n)-[:HAS_NAME]->(m)";

                    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                    $result = $query->getResultSet();
                  }
                  else { // later names with another NAME tag
                    $query_string = "MATCH (n:Person {id:'" . $id . 
                          "'})-[:HAS_NAME]->(m) SET m.later_names='" . $names[1] . "'";

                    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                    $result = $query->getResultSet();
                  }
                  break;
                case "ALIA":
                  $names = nametrim($arg0);
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:HAS_NAME]->(m) SET m.later_names='" . $names . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "BIRT":
                  $event = "BIRT";
                  break;
                case "CHR":
                  $event = "CHR";
                  break;
                case "CONF":
                  $event = "CONF";
                  break;
                case "DEAT":
                  $event = "DEAT";
                  break;
                case "BURI":
                  $event = "BURI";
                  break;
                case "EMIG":
                  $event = "EMIG";
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) CREATE (m:Emig) MERGE (n)-[:MOVED_TO]->(m)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "OCCU":
                  $event = "OCCU";
                  $occu_prev = $arg0; // CONC/CONT possible
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) CREATE (m:Occu {occupation:'" . $arg0 .
                        "'}) MERGE (n)-[:OCCUPATION]->(m)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "NOTE":
                  $event = "NOTE";
                  $note_prev = $arg0; // CONC/CONT possible
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) CREATE (m:Note {note:{note}}) MERGE (n)-[:NOTE]->(m)";

                  $query_array = array('note' => $arg0);

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                  $result = $query->getResultSet();

                  break;
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) CREATE (m:Todo {description:{description}}) MERGE (n)-[:TODO]->(m)";

                  $query_array = array('description' => $arg0);

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                  $result = $query->getResultSet();

                  break;
                case "CHAN":
                  $event = "CHAN";
                  break;
                case "EVEN":
                  $event = "EVEN";
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) CREATE (m:Event {event:'" . $arg0 .
                        "'}) MERGE (n)-[:EVENT]->(m)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "ADDR":
                  $event = "ADDR";
                  break;
                case "RESI":
                  $event = "RESI";
                  break;
                case "SOUR":
                  $event = "SOUR";
                  // Genus Senior SOUR tag
                  break;
                case "FAMC":
                case "FAMS":
                case "LANG":
                case "STAT":
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // switch $key
              break;

            case "FAM":
              switch ($key)  {
                case "HUSB":
                  $husb = idtrim($arg0);
                  $query_string = "MATCH (m:Marriage {id:'" . $fam_id . 
                        "'}), (h:Person {id:'" . $husb . 
                        "'}) MERGE (h)-[:MARRIED]->(m)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "WIFE":
                  $wife = idtrim($arg0);
                  $query_string = "MATCH (m:Marriage {id:'" . $fam_id . 
                        "'}), (w:Person {id:'" . $wife .
                        "'}) MERGE (w)-[:MARRIED]->(m)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "CHIL":
                  $chil = idtrim($arg0);
                  $query_string = "MATCH (c:Person {id:'" . $chil . 
                        "'}), (h:Person {id:'" . $husb . 
                        "'}) MERGE (h)-[:CHILD]->(c) MERGE (c)-[:FATHER]->(h)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  $query_string = "MATCH (c:Person {id:'" . $chil . 
                        "'}), (w:Person {id:'" . $wife .
                        "'}) MERGE (w)-[:CHILD]->(c) MERGE (c)-[:MOTHER]->(w)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();

                  break;
                case "MARR":
                  $event = "MARR";
                  break;
                case "DIV":
                  $event = "DIV";
                  if  (sizeof($a) > 2) {
                    $query_string = "MATCH (m:Marriage {id:'" . $fam_id . 
                        "'}) SET m.divoced_status ='" . $arg0 . "'";

                    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                    $result = $query->getResultSet();
                  }
                  break;
                case "NOTE":
                  $event = "NOTE";
                  $note_prev = $arg0; // CONC/CONT possible
                  $query_string = "MATCH (h:Person {id:'" . $husb . 
                        "'}), (w:Person {id:'" . $wife . 
                        "'}) CREATE (n:Note {note:{note}}) MERGE (h)-[:NOTE]->(n) MERGE (w)-[:NOTE]->(n)";

                  $query_array = array('note' => $arg0);

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                  $result = $query->getResultSet();

                  break;
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $query_string = "MATCH (h:Person {id:'" . $husb . 
                        "'}), (w:Person {id:'" . $wife . 
                        "'}) CREATE (n:Todo {description:{description}}) MERGE (h)-[:TODO]->(n) MERGE (w)-[:TODO]->(n)";

                  $query_array = array('description' => $arg0);

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);

                  $result = $query->getResultSet();

                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // switch $key
              break;

            case "SOUR":
              switch ($key)  {
                case "TITL":
                  $query_string = "MATCH (n:Source {id:'" . $id . 
                    "'}) SET n.title='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "REPO":
                  $repo_id = idtrim($arg0);
                  $query_string = "MATCH (n:Source {id:'" . $id . 
                    "'}) MERGE (p:Repo {id:'" . $repo_id . 
                    "'}) MERGE (p)-[:REPO_SOURCE]->(n)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
              }
              break;

            case "REPO":
              switch ($key)  {
                case "NAME":
                  $query_string = "MATCH (n:Repo {id:'" . $id . 
                    "'}) SET n.name='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "WWW":
                  $query_string = "MATCH (n:Repo {id:'" . $id . 
                    "'}) SET n.www='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              }
              break;

            case "SUBM":
              switch ($key)  {
                case "PHON":
                  $userid = substr($arg0, -4);
                  $user_label = "user" . $userid;
                  $phon_found = true;
                  $userLabel = $sukudb->makeLabel($user_label);
                  break;
                default;
              }
            default;
          }
        }
        else if ($level == 2) {
          switch ($load_type) {
            case "INDI":
              switch ($key)  {
                case "NSFX":
                  $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:HAS_NAME]->(m) SET m.partonymic='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "DATE":
                  $date = explode(' ', $arg0, 3);
                  if (sizeof($date) == 3) {
                    $date[1] = monthtrim($date[1]);
                    $date_str = $date[2] . "." . $date[1] . "." . $date[0];
                  }
                  else {
                    $date_str = $arg0;
                  }
                  switch ($event) {
                    case "BIRT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) SET n.birth_date='" . $date_str . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "CHR":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) SET n.christen_date='" . $date_str . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) SET n.confirmation_date='" . $date_str . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) SET n.death_date='" . $date_str . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) SET n.buried_date='" . $date_str . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "CHAN":
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "PLAC":
                  switch ($event) {
                    case "BIRT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:BIRTH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CHR":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:CHRISTEN_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:CONFIRMATION_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:DEATH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:BURIED_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "EMIG":
                      $emig_prev = $arg0; // CONC/CONT possible
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:MOVED_TO]->(m) SET m.moved_to='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "EVEN":
                      $even_prev = $arg0; // CONC/CONT possible
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:EVEN]->(m) SET m.data='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    case "RESI":
                       break;
                   default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "SOUR":
                  switch ($event) {
                    case "BIRT":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Source {id:'" . $sour_id . 
                        "'}) MERGE (n)-[:BIRTH_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Source {id:'" . $sour_id . 
                        "'}) MERGE (n)-[:CONFIRMATION_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Source {id:'" . $sour_id . 
                        "'}) MERGE (n)-[:DEATH_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Source {id:'" . $sour_id . 
                        "'}) MERGE (n)-[:BURIED_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  }
                  break;
                case "CONC":
                  switch ($event) {
                    case "OCCU":
                      $occu_prev = $occu_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:OCCUPATION]->(o) SET o.occupation='" . $occu_prev . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                    break;
                    case "NOTE":
                      $note_prev = $note_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:NOTE]->(o) SET o.note={note}";

                      $query_array = array('note' => $note_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();

                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:TODO]->(o) SET o.description={description}";

                      $query_array = array('description' => $todo_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();

                      break;
                    case "SOUR":
                      // Genus Senior SOUR tag
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CONT":
                  switch ($event) {
                    case "NOTE":
                      $note_prev = $note_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:NOTE]->(o) SET o.note={note}";

                      $query_array = array('note' => $note_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();

                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:TODO]->(o) SET o.description={description}";

                      $query_array = array('description' => $todo_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();

                     break;
                    case "SOUR":
                      // Genus Senior SOUR tag
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CAUS":
                  switch ($event) {
                    case "DEAT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:EVEN]->(o) SET o.death_cause='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "TYPE":
                  switch ($event) {
                    case "EVEN":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:EVEN]->(o) SET o.type='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();

                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "POST":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CITY":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CTRY":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // switch $key
              break;
            case "FAM":
              switch ($key)  {
                case "DATE":
                  $date = explode(' ', $arg0, 3);
                  if (sizeof($date) == 3) {
                    $date[1] = monthtrim($date[1]);
                    $date_str = $date[2] . "." . $date[1] . "." . $date[0];
                  }
                  else {
                    $date_str = $arg0;
                  }
                  switch ($event) {
                    case "MARR":
                      if (sizeof($date) == 3) {
                        $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                          "'}) SET n.married_date='" . $date_str . "'";

                        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                        $result = $query->getResultSet();
                     }
                      else {
                        $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                          "'}) SET n.married_status='" . $arg0 . "'";

                        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                        $result = $query->getResultSet();
                      }
                      break;
                    case "DIV":
                      if (sizeof($date) == 3) {
                        $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                          "'}) SET n.divoced_date='" . $date_str . "'";

                        $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                        $result = $query->getResultSet();
                      }
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "PLAC":
                  switch ($event) {
                    case "MARR":
                      $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                        "'}) MERGE (p:Place:" . $user_label . " {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:MARRIAGE_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "NOTE":
                  switch ($event) {
                    case "MARR":
                      $note_prev = $arg0; // CONC/CONT possible
                      $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                        "'}) CREATE (p:Note {note:{note}}) MERGE (n)-[:NOTE]->(p)";

                      $query_array = array('note' => $arg0);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                      break;
                  } // $event
                  break;
                case "_TODO":
                  switch ($event) {
                    case "MARR":
                      $todo_prev = $arg0; // CONC/CONT possible

                      $query_string = "MATCH (n:Marriage {id:'" . $fam_id . 
                        "'}) CREATE (m:Todo {description:{description}}) MERGE (n)-[:TODO]->(m)";

                      $query_array = array('description' => $todo_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                    break;
                  } // $event
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // $key
            default;
          }
        }
        else if ($level == 3) {
          switch ($load_type) {
            case "INDI":
              switch ($key)  {
                case "PAGE":
                  switch ($event) {
                    case "BIRT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[r:BIRTH_SOURCE]->(p:Source {id:'" . $sour_id . 
                        "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[r:CONFIRMATION_SOURCE]->(p:Source {id:'" . $sour_id . 
                        "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[r:DEATH_SOURCE]->(p:Source {id:'" . $sour_id . 
                        "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[r:BURIED_SOURCE]->(p:Source {id:'" . $sour_id . 
                        "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CONC":
                  switch ($event) {
                    case "EMIG":
                      $emig_prev = $emig_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:MOVED_TO]->(m) SET m.moved_to='" . $emig_prev . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "EVEN":
                      $even_prev = $even_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:EVEN]->(m) SET m.data='" . $even_prev . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CONT":
                  switch ($event) {
                    case "EMIG":
                      $emig_prev = $emig_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:MOVED_TO]->(m) SET m.moved_to='" . $emig_prev . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "EVEN":
                      $even_prev = $even_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:EVEN]->(m) SET m.data='" . $even_prev . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // $key
              break;
            case "FAM":
              switch ($key)  {
                case "CONC":
                  switch ($event) {
                    case "NOTE":
                      $note_prev = $note_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:NOTE]->(m) SET m.note={note}";

                      $query_array = array('note' => $note_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                      break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:TODO]->(m) SET m.description={description}}";

                      $query_array = array('description' => $todo_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                      break;
                    case "MARR":
                      // Genus Senior SOUR tag
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CONT":
                  switch ($event) {
                    case "NOTE":
                      $note_prev = $note_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:NOTE]->(m) SET m.note={note}";

                      $query_array = array('note' => $note_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                      break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'})-[:TODO]->(m) SET m.description={description}}";

                      $query_array = array('description' => $todo_prev);

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
                      $result = $query->getResultSet();
                      break;
                    case "MARR":
                      // Genus Senior SOUR tag
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                default;
                  echo "Unknown tag " . $key . " on line: " . $n . "\n";
                  $event = "";
              } // $key
            default;
          }
        }
      } // while feof
    }
    echo "</p>\n";
			
    fclose($file_handle);
    echo "<p><em>{$file_name} {$n} rivi&auml; luettu</em></p>";
    echo "<p><em>{$n_indi} henkilö&auml;, {$n_fam} perhett&auml;,";
    echo "{$n_sour} l&auml;hdett&auml; ja {$n_repo} repoa tallennettu</em></p>";
  }
  else {
    print_r($errors);
  }

/*-------------------------- Tiedoston valintalomake ----------------------------*/
?>

<form action="" method="POST" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<h2>Anna ladattava gedcom-tiedosto</h2>
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
