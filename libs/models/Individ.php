<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * User class for carrying individ operations
 *

 * @author jh
 */
class Individ {

  private $id;
  private $firstname;
  private $lastname;
  private $laternames;
  private $gender;
  private $birthdate;
  private $birthplace;
  private $deathdate;
  private $deathplace;

  /**
   * Create a new Individ
   * @param string $id
   */
  public function __construct($id) {
    $this->id = $id;
  }

  /**
   * Get an individ by it's id
   * @param string $id
   * @return \Individ
   */
  public static function getIndivid($id) {
    // Tietokannan lukeminen tähän
    return new Individ("$id");
  }

  public static function getAllIndivids() {
    $list = [];
    // Tietokannan lukeminen tähän
    return $list;
  }

  /**
   * Return all individs with given lastname
   * @param Everyman\Neo4j\Client $sukudb
   * @param string $userid    current user
   * @param string $lastname  search name
   * @param int $method       0 = full name, 1 = match from beginning of name
   * @return list
   */
  static public function findByLastname($sukudb, $userid, $lastname, $method) {
    // This is the array of the individuals to be returned 
    $indi_list = []; 
    
    // 1. Find matching individuals
    
    if ($method == 0) {
      // Find Individuals whose last name is exactly == $lastname
      $query_string = "MATCH (n:Name:" . $userid . ")<-[:HAS_NAME]-(id:Person:" . $userid . ") "
              . "WHERE n.last_name={name} OR n.later_names={name} "
              . "RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('name' => $lastname);
    } else {
      // Find Individuals whose last name matches $lastname
      $input_wildcard = $lastname . '.*';
      $query_string = "MATCH (n:Name:" . $userid . ")<-[:HAS_NAME]-(id:Person:" . $userid . ") "
              . "WHERE n.last_name=~{wildcard} OR n.later_names=~{wildcard} "
              . "RETURN id, n ORDER BY n.last_name, n.first_name";

      $query_array = array('wildcard' => $input_wildcard);
    }
    $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string, $query_array);
    $result = $query->getResultSet();

    // 2. Store each individual's id and names

    $i = 0;
    foreach ($result as $rows) {
      $indi = new Individ($rows[0]->getProperty('id')); // itse asiassa kutsuu self::__construct($id)
      $indi->setFirstname($rows[1]->getProperty('first_name'));
      $indi->setLastname($rows[1]->getProperty('last_name'));
      $indi->setLaternames($rows[1]->getProperty('later_names'));
      echo "<!-- Debug 2: [$i] =" . $indi . " -->\n";   // kutsuu automaattisesti $indi->__toString()
      $indi_list[$i++] = $indi;
    }
    
    // 3. Store birth dates

    foreach ($indi_list as $i => $indi) {
      echo "<!-- Debug 3: [$i] =" . $indi . " -->\n";
      $id = $indi->getId();
      $query_string = "MATCH (n:Person:" . $id . ")-[:BIRTH]->(b) "
              . "WHERE n.id='" . $id . "' RETURN b";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows) {
        $indi->setBirthdate($rows[0]->getProperty('birth_date'));
      }
    }
    
    // 4. Store Birth places

    foreach ($indi_list as $i => $indi) {
      $id = $indi->getId();
      $query_string = "MATCH (n:Person:" . $id . ")-[:BIRTH]->(b)-[:BIRTH_PLACE]->(p) "
              ."WHERE n.id='" . $id . "' RETURN p";
      $query = new Everyman\Neo4j\Cypher\Query($sukudb, $query_string);
      $result = $query->getResultSet();

      foreach ($result as $rows) {
        $indi->setBirthplace($rows[0]->getProperty('name'));
      }
    }

    // 5. Return list of individuals found
    
    return $indi_list;
  }

  /*
   * ---------- Private functions ----------
   */
  
  /**
   * Save name trimmed, empty name 'N'
   * @param string $param
   * @return string
   */
  private function formatName($param) {
    if (isset($param) && trim($param) != '') {
      return trim($param);
    } else {
      return 'N';
    }
  }

  /*
   * ---------- Getters and setters ----------
   */

  public function getId() {
    return $this->id;
  }

  public function getFirstname() {
    return $this->firstname;
  }

  public function getLastname() {
    return $this->lastname;
  }

  public function getLaternames() {
    return $this->laternames;
  }
  
  public function getGender() {
    return $this->gender;
  }

  public function getBirthdate() {
    return $this->birthdate;
  }

  public function getBirthplace() {
    return $this->birthplace;
  }

  public function getDeathdate() {
    return $this->deathdate;
  }

  public function getDeathplace() {
    return $this->deathplace;
  }

  /**
   * 
   * @todo  Tätä ei pitäisi olla eikä käyttää, vaan asettaa id luotaessa: new Individ('I123');
   *        Ei kai olemassa olevan henkilön id:tä koskaan muuteta?
   */
  public function setId($param) {
    $this->id = $param;
  }

  public function setFirstname($param) {
    if ($param != "") {
      $this->firstname = $this->formatName($param);
    } else {
      $this->firstname = "-";
    }
  }

  public function setLastname($param) {
    if ($param != "") {
      $this->lastname = $this->formatName($param);
    } else {
      $this->lastname = "-";
    }
  }

  public function setLaternames($param) {
    if ($param != "") {
      $this->laternames = trim($param);
    } else {
      $this->laternames = "-";
    }
/*    if (isset($param)) {
      $this->laternames = trim($param);
    } else {
      throw InvalidArgumentException('Invalid null argument, not set');
    }
*/
  }

  public function setGender($param) {
    if (isset($param)) {
      switch ($param) {
        case 'M':
        case 'F':
        case 'U':
          $this->gender = $param;
          break;
        case 'N':
          $this->gender = 'F';
          break;
      }
    }
    throw new InvalidArgumentException("Unknown gender '$param' not set");
  }

  public function setBirthdate($birthdate) {
    $this->birthdate = $birthdate;
  }

  public function setBirthplace($birthplace) {
    $this->birthplace = $birthplace;
  }

  public function setDeathdate($deathdate) {
    $this->deathdate = $deathdate;
  }

  public function setDeathplace($deathplace) {
    $this->deathplace = $deathplace;
  }
  
  /*
   * ---------- Display functions ----------
   */

  /**
   * Display this individ
   * @return string
   */
  public function __toString() {
    return "$this->lastname, $this->firstname "
            . $this->genderToString()
            . " [$this->id] ($this->birthdate - $this->deathdate)";
  }

  /**
   * Display gender as a symbol
   * @return string
   */
  public function genderToString() {
    if (isset($this->gender)) {
      switch ($this->gender) {
        case 'M':
          return '♂';
        case 'F':
          return '♀';
        default:
          return $this->gender;
      }
    } else {
      return '-';
    }
  }

}
