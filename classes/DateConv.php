<?php

/**
 * Metodeja päivämäärämuunnoksiin
 *
 * @author jm
 */
class DateConv {

  const DELIM = '.'; // In the db

  static function fromGed($gedDate) {
    /**
     *  Convert Gedcom Date "3 JAN 1918" to "1918-01-03"
     */
    $date = explode(' ', $gedDate, 3);
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
        $year = $gedDate;
        $month = 'XXX';
        $day = '00';
        break;
    }
    if (strlen($year) != 4) {
      echo "Warning: DateConv: Invalid gedcom date \"$gedDate\"";
      return '';
    }
    // Day, always 2 numbers
    $day = $day;
    if (strlen($day) == 1) {
      $day = '0' . $day;
    }
    // Month as number
    switch ($month) {
      case "JAN":
      case "TAM":
        $month_num = "01";
        break;
      case "FEB":
      case "HEL":
        $month_num = "02";
        break;
      case "MAR":
        $month_num = "03";
        break;
      case "APR":
      case "HUH":
        $month_num = "04";
        break;
      case "MAY":
      case "TOU":
        $month_num = "05";
        break;
      case "JUN":
      case "KES":
        $month_num = "06";
        break;
      case "JUL":
      case "HEI":
        $month_num = "07";
        break;
      case "AUG":
      case "ELO":
        $month_num = "08";
        break;
      case "SEP":
      case "SYY":
        $month_num = "09";
        break;
      case "OCT":
      case "LOK":
        $month_num = "10";
        break;
      case "NOV":
        $month_num = "11";
        break;
      case "DEC":
      case "JOU":
        $month_num = "12";
        break;
      default;
        $month_num = "00";
    }
    return $year . self::DELIM . $month_num . self::DELIM . $day;
  }

  static function toDisplay($date) {
    /**
     * Convert Date "1918-01-03" to "3.1.1918"
     */
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
        break;
      case 2:
        if ($a[1] == 0) { // No month
          return $a[0];   // Year only 1918
        } else { // Month . Year
          return '?.' . ($a[1] + 0) . '.' . $a[0]; // ?.1.1918
        }
        break;
      case 1:
        return $a[0];   // Year only 1918
        break;
      case 0:
        return $date;
      default:
        break;
    }
  }

}
