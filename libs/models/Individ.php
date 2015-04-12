<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * User class for carrying individ properties
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
   * @param string $lastname
   * @param int $method 0 = full name, 1 = match from beginning of name
   * @return list
   */
  public function findByLastname($lastname, $method) {
    $list = [];
    // Tietokannan lukeminen tähän
    return $list;
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

  public function setId($param) {
    $this->id = $param;
  }

  public function setFirstname($param) {
//    $this->firstname = this->formatName($param);
    $this->firstname = $param;
  }

  public function setLastname($param) {
//    $this->lastname = $this->formatName($param);
    $this->lastname = $param;
  }

  public function setLaternames($param) {
    $this->laternames = trim($param);
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
  public function display() {
    return "$this->lastname, $this->firstname "
            . $this->displayGender()
            . " [$this->id] ($this->birthdate - $this->deathdate)";
  }

  /**
   * Display gender as a symbol
   * @return string
   */
  public function displayGender() {
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
