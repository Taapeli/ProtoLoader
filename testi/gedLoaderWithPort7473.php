<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Taapeli aineiston luku kantaan</title>
<link rel="stylesheet" type="text/css" href="../style.css" />
</head>

<body>
<div  class="goback">
  <a href="index.php">Paluu</a></div>
<h1>Taapeli testilataus</h1>
<p>Luetaan gedcom-tiedostoa.</p>
<?php

  include "../inc/dbconnect.php";

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

      $sukudb = new Everyman\Neo4j\Client('neo4j35029-Taademo2.jelastic.elastx.net', 7473);

      $idLabel = $sukudb->makeLabel('Person');
      $nameLabel = $sukudb->makeLabel('Name');
      $marriageLabel = $sukudb->makeLabel('Marriage');
      $sourceLabel = $sukudb->makeLabel('Source');
      $repoLabel = $sukudb->makeLabel('Repo');

      $n = 0;
      $phon_found = false; // If phonenumber exists it will be used as an userid
      $n = $n_indi = $n_fam = $n_sour = $n_repo = 0; // How many lines were read
      $load_type = ""; // values: INDI, FAM, SOUR, REPO 

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
          if (sizeof($a) > 2) {
            switch ($arg0)  {
              case "INDI":
                if (!$phon_found) {
                  echo "Tiedostoa ei voitu tallentaa tietokantaan gedcom-tiedostosta puuttuvan puhelinnumeron vuoksi!\n\n";
                  echo "Lisää puhelinnumero käyttäjätietoihin, esim. 1 PHON 040 123 4567\n";
                  exit;
                }
                $n_indi++;
                $load_type = "INDI";
                $name_cnt = 0;
                $person[$id] = $sukudb->makeNode()
                   ->setProperty('id', $id)
                   ->save();
                $idLabels = $person[$id]->addLabels(array($idLabel));
                $userLabels = $person[$id]->addLabels(array($userLabel));
                break;
              case "FAM":
                $n_fam++;
                $load_type = "FAM";
                break;
              case "SOUR":
                $n_sour++;
                $load_type = "SOUR";
                break;
              case "REPO":
                $n_repo++;
                $load_type = "REPO";
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
                    $userLabels = $name->addLabels(array($userLabel));

                    $rel = $person[$id]->relateTo($name, 'HAS_NAME')->save();
                  }
                  else { // later names with another NAME tag
                    $later_name = $name
                      ->setProperty('later_names', $names[1])
                    ->save();
                  }
                  break;
                case "ALIA":
                  $names = nametrim($arg0);
                  $alia = $name->setProperty('later_names', $names)
                    ->save();
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
                  $note_prev = $arg0; // CONC/CONT possible
                  $note = $sukudb->makeNode()
                    ->setProperty('note', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($note, 'NOTE')->save();
                  break;
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $todo = $sukudb->makeNode()
                    ->setProperty('description', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($todo, 'TODO')->save();
                  break;
                case "SOUR":
                  $event = "SOUR";
                  echo "1 SOUR is ignored (syntax error) on line " . $n . "\n";
                  break;
                case "CHAN":
                  $event = "CHAN";
                  break;
                case "EVEN":
                  $event = "EVEN";
                  $even = $sukudb->makeNode()
                    ->setProperty('event', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($even, 'EVENT')->save();
                  break;
                case "ADDR":
                  $event = "ADDR";
                  break;
                case "RESI":
                  $event = "RESI";
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
                  $marr = $sukudb->makeNode()
                    ->setProperty('id', $id)
                    ->save();
                    $marriageLabels = $marr->addLabels(array($marriageLabel));
                    $userLabels = $marr->addLabels(array($userLabel));
                  break;
                case "WIFE":
                  $wife = idtrim($arg0);
                  $rel_husb = $person[$husb]->relateTo($marr, 'MARRIED')->save();
                  $rel_wife = $person[$wife]->relateTo($marr, 'MARRIED')->save();
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
                  if  (sizeof($a) > 2) {
                    $div_date = $marr
                      ->setProperty('divoced_status', $arg0)
                      ->save();
                  }
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
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $todo = $sukudb->makeNode()
                    ->setProperty('description', $arg0)
                    ->save();
                  $rel_husb = $person[$husb]->relateTo($todo, 'TODO')->save();
                  $rel_wife = $person[$wife]->relateTo($todo, 'TODO')->save();
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
                  $nsfx = $name->setProperty('partonymic', $arg0)->save();
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
                      $person[$id]->setProperty('birth_date', $date_str)
                        ->save();
                      break;
                    case "CHR":
                      $person[$id]->setProperty('christen_date', $date_str)
                        ->save();
                      break;
                    case "CONF":
                      $person[$id]->setProperty('confirmation_date', $date_str)
                        ->save();
                      break;
                    case "DEAT":
                      $person[$id]->setProperty('death_date', $date_str)
                        ->save();
                      break;
                    case "BURI":
                      $person[$id]->setProperty('buried_date', $date_str)
                        ->save();
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
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:BIRTH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CHR":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:CHRISTEN_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:CONFIRMATION_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:DEATH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:BURIED_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "EMIG":
                      $emig_prev = $arg0; // CONC/CONT possible
                      $emig_plac = $emig
                        ->setProperty('moved_to', $arg0)
                        ->save();
                      break;
                    case "EVEN":
                      $even_prev = $arg0; // CONC/CONT possible
                      $even_plac = $even
                        ->setProperty('data', $arg0)
                        ->save();
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
                    case "SOUR":
                      echo "2 CONC is ignored (syntax error) on line " . $n . "\n";
                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_conc = $source
                        ->setProperty('description', $todo_prev)
                        ->save();
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
                      $note_cont = $note
                        ->setProperty('note', $note_prev)
                        ->save();
                      break;
                    case "SOUR":
                      echo "2 CONT is ignored (syntax error) on line " . $n . "\n";
                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_cont = $source
                        ->setProperty('description', $todo_prev)
                        ->save();
                     break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "CAUS":
                  switch ($event) {
                    case "DEAT":
                      $deat_cause = $even
                        ->setProperty('death_cause', $arg0)
                        ->save();
                      break;
                    default;
                      echo "Unknown tag " . $key . " on line: " . $n . "\n";
                      $event = "";
                  } // $event
                  break;
                case "TYPE":
                  switch ($event) {
                    case "EVEN":
                      $even_type = $even
                        ->setProperty('type', $arg0)
                        ->save();
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
                        $marr_date = $marr
                          ->setProperty('married_date', $date_str)
                          ->save();
                      }
                      else {
                        $marr = $marr
                          ->setProperty('married_status', $arg0)
                          ->save();
                      }
                      break;
                    case "DIV":
                      if (sizeof($date) == 3) {
                        $div_date = $marr
                          ->setProperty('divoced_date', $date_str)
                          ->save();
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
                      $query_string = "MATCH (n:Marriage {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
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
                      $note = $sukudb->makeNode()
                        ->setProperty('note', $arg0)
                        ->save();
                      $rel_marr = $marr->relateTo($note, 'NOTE')->save();
                    break;
                  } // $event
                  break;
                case "_TODO":
                  switch ($event) {
                    case "MARR":
                      $todo_prev = $arg0; // CONC/CONT possible
                      $todo = $sukudb->makeNode()
                        ->setProperty('description', $arg0)
                        ->save();
                      $rel_marr = $marr->relateTo($todo, 'TODO')->save();
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
                      $emig_conc = $emig
                        ->setProperty('moved_to', $emig_prev)
                        ->save();
                      break;
                    case "EVEN":
                      $even_prev = $even_prev . " " . $arg0;
                      $even_conc = $even
                        ->setProperty('data', $even_prev)
                        ->save();
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
                      $emig_cont = $emig
                        ->setProperty('moved_to', $emig_prev)
                        ->save();
                      break;
                    case "EVEN":
                      $even_prev = $even_prev . " " . $arg0;
                      $even_cont = $even
                        ->setProperty('data', $even_prev)
                        ->save();
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
                      $note_conc = $note
                        ->setProperty('note', $note_prev)
                        ->save();
                      break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_conc = $todo
                        ->setProperty('description', $todo_prev)
                        ->save();
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
                      $note_cont = $note
                        ->setProperty('note', $note_prev)
                        ->save();
                      break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_cont = $todo
                        ->setProperty('description', $todo_prev)
                        ->save();
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
