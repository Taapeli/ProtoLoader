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
  private $birthdate;
  private $birthplace;
  private $deathdate;
  private $deathplace;

  /**
   * Create a new Individ
   * @param string $id
   */
  public function __construct($individ_id) {
    $this->id = $individ_id;
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
   * @return list
   */
  public function listNames($lastname) {
    // Tietokannan lukeminen tähän
    return $list;
  }

  /*
   * Getters and setters
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

  public function getLatername() {
    return $this->latername;
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

  public function setFirstname($firstname) {
    $this->firstname = $firstname;
  }

  public function setLastname($lastname) {
    $this->lastname = $lastname;
  }

  public function setLatername($latername) {
    $this->latername = $latername;
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
  
}
