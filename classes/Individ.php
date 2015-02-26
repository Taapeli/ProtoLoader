<?php

/**
 * Luokat henkilöiden ja perheiden tietoja varten
 * Joku vanha - Kovasti kesken!
 * 
 * @author JMä 2015
 * 
 */
class Individ {

  private $id;
  private $etunimi = "";
  private $sukunimi = "";
  private $sukupuoli = "";
  private $synt = "";
  private $kuol = "";

  // Nimi näyttömuodossa: tyhjä = "N"
  private function showStr($x) {
    if ($x == "") {
      return 'N';
    } else {
      return $x;
    }
  }

  public function setEtunimi($value) {
    $this->etunimi = $value;
  }

  public function getEtunimi() {
    return $this->etunimi;
  }

  public function setSukunimi($value) {
    $this->sukunimi = $value;
  }

  public function getSukunimi() {
    return $this->etunimi;
  }

  public function setSynt($value) {
    $this->synt = $value;
  }

  public function getSynt() {
    return $this->synt;
  }

  public function setKuol($value) {
    $this->kuol = $value;
  }

  public function getKuol() {
    return $this->kuol;
  }

  // Päivämäärä tulostetaan suomalaisittain
  public static function showDate($d) {
    if ($d == "") {
      return "-";
    }
    if (length($d) != 10) {
      return $d;
    }
    // Voisi olla parsittavissa päivämääränä
    $a = preg_split("[-.]", $d);
    if (length($a) != 3) {
      return "Virheelinen pvm=$d";
    }
    if (length($a[2]) == 4) {
      return "$a[0].$a[1].$a[2]";
    } else {
      return "$a[2].$a[1].$a[0]";
    }
  }

  public static function show() {
    return showStr($this->etunimi) . ", " . showStr($this->sukunimi)
            . ", $this->sukupuoli ($this->synt - $this->kuol)";
  }

  public static function showHtmlRow() {
    return "<tr><td>" . showStr($this->etunimi) . ", " . showStr($this->sukunimi)
            . "</td><td>$this->sukupuoli</td><td>$this->synt - $this->kuol</td></tr>";
  }

}
