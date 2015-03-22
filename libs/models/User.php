<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * User class for carrying user properties
 *
 * @todo Password shold be crypted in some level
 * @solution We can use a php function md5 with salting constants.
 * @example $salt1 = 'tA!pE; $salt2 = 'Li2o1S'; 
 * @example $crypted_password = md5($salt1$password$salt2);

 * @author jm
 */
class User {

  private $id;      // User name
  private $passwd;  // User password
  private $role;    // User type
  private $stats;   // User statistics as an array of keyword => value pairs
  static protected $roles = [
      0 => 'Unknown',
      1 => 'Reader',
      2 => 'Editor', // Default
      3 => 'Revisor',
      4 => 'Supervisor',
      5 => 'Administrator',
      9 => 'System'  // The owner of base Taapeli data
  ];

  /**
   * Create a new User
   * @param string $user_id
   * @param string $password
   * @param int $type
   */
  public function __construct($user_id, $password = '', $type = 2) {
    //echo "<br />construct($user_id, $password, $type)\n";
    $this->id = $user_id;
    $this->passwd = $password;
    $this->stats = [];
    if (array_key_exists($type, self::$roles)) {
      $this->role = $type;
    } else {
      $this->role = 0;
    }
  }

  /**
   * Get an user by it's id
   * @param string $id
   * @return \User
   */
  public static function getUser($id) {
    // Tietokannan lukeminen tähän
    return new User("$id", '', 2);
  }

  public static function getAllUsers() {
    $list = [];
    for ($i = 0; $i <= 5; $i++) {
      // Tietokannan lukeminen tähän
      $user = new User("user$i", '', $i);
      $list[] = $user;
    }
    return $list;
  }

  /**
   * Check if given passwd belongs to this user
   * @param string $passwd
   * @return boolean
   */
  public function checkPasswd($passwd) {
    return ($this->passwd == $passwd);
  }

  /**
 * Get an array of user statistics
 * @return array of {key => value} pairs
 */
  public function getStats() {
    // Tietokannan lukeminen tähän
    $this->stats['NODE PERSON'] = 2900;
    $this->stats['NODE NAME'] = 3100;
    $this->stats['REL BIRTH'] = 120;
    $this->stats['REL CHILD'] = 80;
    return $this->stats;
  }

  /**
   * For debug purposes
   * @return string Description of this user data
   */
  public function dump() {
    $ret = "Käyttäjä=$this->id rooli=" . $this->displayRole();
    foreach ($this->stats as $key => $s) {
      $ret .= '\n\t' . $key . ' => ' . $val;
    }
    return $ret;
  }

  /*
   * Getters and setters
   */

  public function getUserid() {
    return $this->id;
  }

  public function getRole() {
    return $this->role;
  }

  public function displayRole() {
    return self::$roles[$this->role];
  }

  public function setPassword($passwd) {
    $this->passwd = $passwd;
  }
  
}
