<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php session_start(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - aineiston luku kantaan</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>

    <body>
        <?php
        include 'inc/checkUserid.php';
        include 'inc/start.php';
        include 'libs/models/GedDateParser.php';
        include 'inc/dbconnect.php';
        include 'inc/gedcomTags.php';
        /*
         * -- Content page starts here -->
         */

        echo '<h1>Ladataan gedcom-tiedosto järjestelmään</h1>';

/*
 * -------------------------- Tiedoston luku ----------------------------
*
* 	   Simple file Upload system with PHP by Tech Stream
*      http://techstream.org/Web-Development/PHP/Single-File-Upload-With-PHP
*/

  if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
    // Tiedoston käsittelyn muuttujat
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
  //$max_lines = $_POST["maxlines"];
    $x=explode('.',$file_name);
    $file_ext = strtolower(end($x));	
    $expensions= array("ged","degcom","txt"); 

    if (!in_array($file_ext, $expensions)) {
      echo "<p>Väärä tiedostopääte. Anna Gedcom -tiedosto, jonka pääte on " .
            implode(', ', $expensions) . "</p></body></html>";
      die;
    }

    echo "<p><em>Ladattu tiedostiedosto " . $file_name 
	. " (size=" . $file_size . ")</em><p>\n";

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

      $idLabel = $sukudb->makeLabel('Person');
      $nameLabel = $sukudb->makeLabel('Name');
      $marriageLabel = $sukudb->makeLabel('Marriage');
      $birthLabel = $sukudb->makeLabel('Birth');
      $christienLabel = $sukudb->makeLabel('Christien');
      $confirmationLabel = $sukudb->makeLabel('Confirmation');
      $deathLabel = $sukudb->makeLabel('Death');
      $buriedLabel = $sukudb->makeLabel('Buried');
      $sourceLabel = $sukudb->makeLabel('Source');
      $repoLabel = $sukudb->makeLabel('Repo');
      $userLabel = $sukudb->makeLabel($userid);

      // How many lines were read
      $n = $n_skip = $n_indi = $n_fam = $n_sour = $n_repo = 0; 

      $load_type = ""; // values: INDI, FAM, SOUR, REPO 
      $event = "";

      // Store the userid into the database as an single node without any connections
      $query_string = "MERGE (u:Userid {userid:'" . $userid . 
                    "'})";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

/*
** Avataan tiedosto
*/
      $file_handle = fopen($file_tmp, "r");
      $skip = '9';  // Skip this level and higher
      $ged = new GedDateParser();
      
      while (!feof($file_handle)) {
        $line = fgets($file_handle);
        $n++;
        $a = explode(' ', $line, 3);
        $level = $a[0];
        
        if ($level > $skip) {
          // Preceeding upper level tag has been skipped
          echo "<!-- skip $line -->";
          $n_skip++;
          continue;
        } else {
          $skip = '9';
        }

        if (sizeof($a) > 1) {
          $key = trim($a[1]);
          if (skipGedcomTag($key)) {
            // This tag and following higher level tags shall be skipped
            $skip = $level;
            echo "<!-- line:$n Skip $line  -->";
            $n_skip++;
            continue;
          }
        } else {
          // echo "\n\nOnly one argument on line: " . $n . "\n";
          $key = "";
        }

        if (sizeof($a) > 2) {
          $arg = $arg0 = trim($a[2]);
        }


        /*------------------------- Level  0  tags ----------------------*/
        if ($level == 0) {
          $id = idtrim($key);
          if (sizeof($a) > 2) {
            switch ($arg0)  {
              case "INDI":
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
                //echo "<!-- FAM0 ($key) $line -->";
                $n_fam++;
                $load_type = "FAM";
                $marr = $sukudb->makeNode()
                  ->setProperty('id', $id)
                  ->save();
                $marriageLabels = $marr->addLabels(array($marriageLabel));
                $userLabels = $marr->addLabels(array($userLabel));
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
              case "NOTE":
                /* @todo Epäsuoran noten (0 @N...@ NOTE) käsittely, tähän tapaan:
                 * 1 NOTE @N0030@
                 * 2 NOTE @N0032@
                 * 0 @N0030@ NOTE Laitoksen nimi epävarma, nykyisellä Koskelantiellä
                 * 0 @N0032@ NOTE Kääntyi uskoon vanhemmiten
                 */
                break;
              default;
                echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                $event = "";
                $load_type = "";
            }
          }
          // echo "id = " . $id . "\n";
        } // if level = 0
        /*------------------------- Level  1  tags ----------------------*/
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
                  $userLabels = $emig->addLabels(array($userLabel));
                  break;
                case "OCCU":
                  $event = "OCCU";
                  $occu_prev = $arg0; // CONC/CONT possible
                  $occu = $sukudb->makeNode()
                    ->setProperty('occupation', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($occu, 'OCCUPATION')->save();
                  $userLabels = $occu->addLabels(array($userLabel));
                  break;
                case "NOTE":
                  $event = "NOTE";
                  $note_prev = $arg0; // CONC/CONT possible
                  $note = $sukudb->makeNode()
                    ->setProperty('note', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($note, 'NOTE')->save();
                  $userLabels = $note->addLabels(array($userLabel));
                  break;
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $todo = $sukudb->makeNode()
                    ->setProperty('description', $arg0)
                    ->save();
                  $rel = $person[$id]->relateTo($todo, 'TODO')->save();
                  $userLabels = $todo->addLabels(array($userLabel));
                  break;
                case "SOUR":
                  $event = "SOUR";
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n:"
                          . " 1 SOUR is ignored (syntax error)</p>";
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
                  $userLabels = $even->addLabels(array($userLabel));
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
                  //echo "<!-- FAM1 ($key) $line skipping -->";
                break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                  $event = "";
              } // INDI switch $key
              break;

            case "FAM":
              //echo "<!-- FAM1 ($key) $line -->";
              switch ($key)  {
                case "HUSB":
                  $husb = idtrim($arg0);
                  break;
                case "WIFE":
                  $wife = idtrim($arg0);
                    if (isset($husb)) {
                        $rel_husb = $person[$husb]->relateTo($marr, 'MARRIED')->save();
                    } else {
                        echo "<p><b>Info</b> gedloader:" . __LINE__ . " line $n: "
                                . "No HUSB in the family $id</p>";
                    }
                  $rel_wife = $person[$wife]->relateTo($marr, 'MARRIED')->save();
                  break;
                case "CHIL":
                  $chil = idtrim($arg0);
                  if (isset($husb)) {
                    $rel = $person[$husb]->relateTo($person[$chil], 'CHILD')->save();
                    $rel = $person[$chil]->relateTo($person[$husb], 'FATHER')->save();
                  } else {
                    echo "<p><b>Warning</b>: gedloader:" . __LINE__ . " line $n: "
                            . "No father in the family $id</p>";
                  }
                  if (isset($wife)) {
                    $rel = $person[$wife]->relateTo($person[$chil], 'CHILD')->save();
                    $rel = $person[$chil]->relateTo($person[$wife], 'MOTHER')->save();
                  } else {
                    echo "<p><b>Warning</b>: gedloader:" . __LINE__ . " line $n: "
                            . "No mother in the family $id</p>";
                  }
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
                  $userLabels = $note->addLabels(array($userLabel));
                  if (isset($husb))
                    $rel_husb = $person[$husb]->relateTo($note, 'NOTE')->save();
                  if (isset($wife))
                    $rel_wife = $person[$wife]->relateTo($note, 'NOTE')->save();
                  break;
                case "_TODO":
                  $event = "TODO";
                  $todo_prev = $arg0; // CONC/CONT possible
                  $todo = $sukudb->makeNode()
                    ->setProperty('description', $arg0)
                    ->save();
                  $userLabels = $todo->addLabels(array($userLabel));
                  if (isset($husb))
                    $rel_husb = $person[$husb]->relateTo($todo, 'TODO')->save();
                  if (isset($wife))
                    $rel_wife = $person[$wife]->relateTo($todo, 'TODO')->save();
                  break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                  $event = "";
              } // FAM switch $key
              break;

            case "SOUR":
              switch ($key)  {
                case "TITL":
                  $query_string = "MATCH (n:Source:" . $userid . " {id:'" . $id . 
                    "'}) SET n.title='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "REPO":
                  $repo_id = idtrim($arg0);
                  $query_string = "MATCH (n:Source:" . $userid . " {id:'" . $id . 
                    "'}) MERGE (p:Repo:" . $userid . " {id:'" . $repo_id . 
                    "'}) MERGE (p)-[:REPO_SOURCE]->(n)";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
              }
              break;

            case "REPO":
              switch ($key)  {
                case "NAME":
                  $query_string = "MATCH (n:Repo:" . $userid . " {id:'" . $id . 
                    "'}) SET n.name='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                case "WWW":
                  $query_string = "MATCH (n:Repo:" . $userid . " {id:'" . $id . 
                    "'}) SET n.www='" . $arg0 . "'";

                  $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                  $result = $query->getResultSet();
                  break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                  $event = "";
              }
              break;
            default;
          } // load_type
        } // if level = 1
        
        /*------------------------- Level  2  tags ----------------------*/
        else if ($level == 2) {
          switch ($load_type) {
            case "INDI":
              switch ($key)  {
                case "NSFX":
                  $nsfx = $name->setProperty('partonymic', $arg0)->save();
                  break;
                case "DATE":
                  $date_str = $ged->fromGed($arg0);
                  switch ($event) {
                    case "BIRT":
                      $even = $sukudb->makeNode()
                        ->setProperty('type', 'Birth')
                        ->setProperty('birth_date', $date_str)
                        ->save();
                      $rel = $person[$id]->relateTo($even, 'BIRTH')->save();

                      $birthLabels = $even->addLabels(array($birthLabel));
                      $userLabels = $even->addLabels(array($userLabel));
                      break;
                    case "CHR":
                      $even = $sukudb->makeNode()
                        ->setProperty('type', 'Christen')
                        ->setProperty('christen_date', $date_str)
                        ->save();
                      $rel = $person[$id]->relateTo($even, 'CHRISTEN')->save();

                      $christenLabels = $even->addLabels(array($christenLabel));
                      $userLabels = $even->addLabels(array($userLabel));
                      break;
                    case "CONF":
                      $even = $sukudb->makeNode()
                        ->setProperty('type', 'Confirmation')
                        ->setProperty('confirmation_date', $date_str)
                        ->save();
                      $rel = $person[$id]->relateTo($even, 'CONFIRMATION')->save();

                      $confirmationLabels = $even->addLabels(array($confirmationLabel));
                      $userLabels = $even->addLabels(array($userLabel));
                      break;
                    case "DEAT":
                      $even = $sukudb->makeNode()
                        ->setProperty('type', 'Death')
                        ->setProperty('death_date', $date_str)
                        ->save();
                      $rel = $person[$id]->relateTo($even, 'DEATH')->save();

                      $deathLabels = $even->addLabels(array($deathLabel));
                      $userLabels = $even->addLabels(array($userLabel));
                      break;
                    case "BURI":
                      $even = $sukudb->makeNode()
                        ->setProperty('type', 'Buried')
                        ->setProperty('buried_date', $date_str)
                        ->save();
                      $rel = $person[$id]->relateTo($even, 'BURIED')->save();

                      $buriedLabels = $even->addLabels(array($buriedLabel));
                      $userLabels = $even->addLabels(array($userLabel));
                      break;
                    case "CHAN":
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // INDI DATE $event
                  break;
                case "PLAC":
                  switch ($event) {
                    case "BIRT":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BIRTH]->(e) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (e)-[:BIRTH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CHR":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:CHRISTEN]->(e) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (e)-[:CHRISTEN_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:CONFIRMATION]->(e) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (e)-[:CONFIRMATION_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:DEATH]->(e) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (e)-[:DEATH_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BURIED]->(e) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (e)-[:BURIED_PLACE]->(p)";

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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // PLAC $event
                  break;
                case "SOUR":
                  switch ($event) {
                    case "BIRT":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BIRTH]->(e) MERGE (p:Source:" . $userid . " {id:'" . $sour_id . 
                        "'}) MERGE (e)-[:BIRTH_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:CONFIRMATION]->(e) MERGE (p:Source:" . $userid . " {id:'" . $sour_id . 
                        "'}) MERGE (e)-[:CONFIRMATION_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:DEATH]->(e) MERGE (p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) MERGE (e)-[:DEATH_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $sour_id = idtrim($arg0);
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BURIED]->(e) MERGE (p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) MERGE (e)-[:BURIED_SOURCE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // SOUR event
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n:"
                            . " 2 CONC is ignored (syntax error)</p>";
                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_conc = $source
                        ->setProperty('description', $todo_prev)
                        ->save();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // CONC $event
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                            . "2 CONT is ignored (syntax error)</p>\n";
                     break;
                    case "TODO":
                      $todo_prev = $todo_prev . " " . $arg0;
                      $todo_cont = $source
                        ->setProperty('description', $todo_prev)
                        ->save();
                     break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // CONT $event
                  break;
                case "CAUS":
                  switch ($event) {
                    case "DEAT":
                      $deat_cause = $even
                        ->setProperty('death_cause', $arg0)
                        ->save();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // CAUS $event
                  break;
                case "TYPE":
                  switch ($event) {
                    case "EVEN":
                      $even_type = $even
                        ->setProperty('type', $arg0)
                        ->save();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // $event
                  break;
                case "POST":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } 
                  break;
                case "CITY":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // $event
                  break;
                case "CTRY":
                  switch ($event) {
                    case "ADDR":
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  }
                  break;
                case 'GIVN': // 1 NAME contains these?
                case 'SURN':
                  break;
                default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                          . "Unknown tag $level $key.<p>\n";
                  $event = "";
              } // switch $key
              break; // End INDI

            case "FAM":
              //echo "<!-- FAM2 ($key) $line -->";
              switch ($key)  {
                case "DATE":
                  $date_str = $ged->fromGed($arg0);
                  switch ($event) {
                    case "MARR":
//                      if (sizeof($date) == 3) {
                        $marr_date = $marr
                          ->setProperty('married_date', $date_str)
                          ->save();
/*                      }
                      else {
                        $marr = $marr
                          ->setProperty('married_status', $arg0)
                          ->save();
                      }
*/
                      break;
                    case "DIV":
//                      if (sizeof($date) == 3) {
                        $div_date = $marr
                          ->setProperty('divoced_date', $date_str)
                          ->save();
//                      }
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // $event
                  break;
                case "PLAC":
                  switch ($event) {
                    case "MARR":
                      $query_string = "MATCH (n:Marriage:" . $userid . " {id:'" . $id . 
                        "'}) MERGE (p:Place {name:'" . $arg0 . 
                        "'}) MERGE (n)-[:MARRIAGE_PLACE]->(p)";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);

                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
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
                      $userLabels = $note->addLabels(array($userLabel));
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
                      $userLabels = $todo->addLabels(array($userLabel));
                    break;
                  } // $event
                  break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                          . "Unknown tag $level $key.<p>\n";
                  $event = "";
              } // $key
            default;
          }
        }
        
        /*------------------------- Level  3  tags ----------------------*/
        else if ($level == 3) {
          switch ($load_type) {
            case "INDI":
              switch ($key)  {
                case "PAGE":
                  switch ($event) {
                    case "BIRT":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BIRTH]->()-[r:BIRTH_SOURCE]->(p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "CONF":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:CONFIRMATION]->()-[r:CONFIRMATION_SOURCE]->(p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "DEAT":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:DEATH]->()-[r:DEATH_SOURCE]->(p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    case "BURI":
                      $query_string = "MATCH (n:Person:" . $userid . " {id:'" . $id . 
                        "'})-[:BURIED]->()-[r:BURIED_SOURCE]->(p:Source:" . $userid . 
                        " {id:'" . $sour_id . "'}) SET r.page='" . $arg0 . "'";

                      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
                      $result = $query->getResultSet();
                      break;
                    default;
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // $event
                  break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                          . "Unknown tag $level $key.<p>\n";
                  $event = "";
              } // $key
              break;

            case "FAM":
              //echo "<!-- FAM3 ($key) $line -->";
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
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
                      echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                              . "Unknown tag $level $event/$key <p>\n";
                      $event = "";
                  } // $event
                  break;
                default;
                  echo "<p><b>Warning</b> gedloader:" . __LINE__ . " line $n: "
                          . "Unknown tag $level $key.<p>\n";
                  $event = "";
              } // $key
              default;
          } // $load_type
        } // if $level = 3
        /*----------------------- No Higher Level tags --------------------*/
        
      } // while feof
    } // if filename given
    else {
      echo "<p>Tyhjä tiedostonimi, ei ladattu.</p></body></html>";
    }
    echo "</p>\n";
			
    fclose($file_handle);
    echo "<p><em>{$file_name} {$n} riviä luettu, tallennettu:</em></p>";
    echo "<p><em>{$n_indi} henkilöä <br />{$n_fam} perhettä <br />"
    . "{$n_sour} lähdettä <br />{$n_repo} arkistoa</em></p>";
    echo "<p><em>Ohitettu {$n_skip} riviä ei-kiinnostavia tageja</em></p>";

/*-------------------------- Tiedoston valintalomake ----------------------------

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

*/
        
  /*
   * --- End of content page ---
   */
include "inc/stop.php";
