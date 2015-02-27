<?php

/**
 * Metodeja päivämäärämuunnoksiin
 * 
 * @todo muunnos syöttämuodosta "5.12.1917" tietokantamuotoon
 * @todo Gedcom-päivämäärämuodot DATE_APPROXIMATED, DATE_PERIOD, DATE_RANGE
 * @todo Kannan erottimeksi '-' kun kanta joskus tyhjennetään
 *
 * @author jm
 */
class DateConv {

  const DELIM = '.'; // Delimiter in the db

  /*
   * Convert Gedcom 1-3 word date "3 JAN 1918" or "3 TAM 1918" 
   * to "1918-01-03".
   * Also partial dates like "JAN 1918" and "1981" are understood.
   */

  static private function fromBasicGed(array $date) {
    static $monthNum = array(
        'JAN' => '01', 'FEB' => '02', 'MAR' => '03',
        'APR' => '04', 'MAY' => '05', 'JUN' => '06',
        'JUL' => '07', 'AUG' => '08', 'SEP' => '09',
        'OCT' => '10', 'NOV' => '11', 'DEC' => '12',
        'TAM' => '01', 'HEL' => '02', 'MAA' => '03',
        'HUL' => '04', 'TOU' => '05', 'KES' => '06',
        'HEI' => '07', 'ELO' => '08', 'SYY' => '09',
        'LOK' => '10', 'JOU' => '12'
    );

    $count = sizeof($date);
    switch ($count) {
      case 3:
        $year = $date[2];
        $month = $date[1];
        $day = $date[0];
        break;
      case 2:
        $year = $date[1];
        $month = $date[0];
        $day = '00';
        break;
      case 1:
        $year = $date[0];
        $month = 'XXX';
        $day = '00';
        break;
      default:
        $year = implode(' ', $date);
        $month = 'XXX';
        $day = '00';
        break;
    }
    if (strlen($year) != 4) {
      echo '<br />Warning: DateConv: Invalid gedcom date "' . implode(' ', $date) . '"<br />';
      return '';
    }
    // Day, always 2 numbers
    if (strlen($day) == 1) {
      $day = '0' . $day;
    }
    // Month as number
    $month_num = isset($monthNum[$month]) ? $monthNum[$month] : '00';

    return $year . self::DELIM . $month_num . self::DELIM . $day;
  }

  /**
   *  Convert Gedcom Date "3 JAN 1918" or "3 TAM 1918" to "1918-01-03"
   */
  static function fromGed($gedDate) {
    $date = explode(' ', $gedDate);
    return self::fromBasicGed($date);
  }

  /**
   * Convert Date "1918-01-03" to "3.1.1918"
   */
  static function toDisplay($date) {
    if (strstr($date, '-')) {
      $a = explode('-', $date, 3);
    } else {
      $a = explode('.', $date, 3);
      /* @todo piste päivämäärässä poistettaneen */
    }

    switch (sizeof($a)) {
      case 3:
        if ($a[2] == 0) { // No day
          if ($a[1] == 0) { // No month
            return $a[0];   // Year only 1918
          } else { // Month . Year
            return '?.' . ($a[1] + 0) . '.' . $a[0]; // ?.1.1918
          }
        }
        return ($a[2] + 0) . '.' . ($a[1] + 0) . '.' . $a[0];  // Normal
      case 2:
        if ($a[1] == 0) { // No month
          return $a[0];   // Year only 1918
        } else { // Month . Year
          return '?.' . ($a[1] + 0) . '.' . $a[0]; // ?.1.1918
        }
      case 1:
        return $a[0];   // Year only 1918
      case 0:
        return $date;
      default:
        break;
    }
  }

  /*
   * Conversion from input field to db format
   */

 // static function fromDisplay($date) {
    /* $Todo fromDisplay($date) */
 //   return $date;
 // }

}
