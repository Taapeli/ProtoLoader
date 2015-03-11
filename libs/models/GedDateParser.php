<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * Description of GedDateParser
 *
 * @author jm
 */
class GedDateParser {

  const DELIM = '-';    // Delimiter in the db

  // For parsing gedcom DATE_VALUE

  private $dateStr;     // string: original gedcom line
  private $tokens;      // array: gedcom input line

  /**
   * Gedcom DATE_VALUE to db format  -  TESTING NEW FUNC
   * 
   * @param string $gedDate
   * @return string
   * @throws InvalidArgumentException
   * @throws Exception  internal parse error
   */
  public function fromGed($gedDate) {
    $this->dateStr = trim($gedDate);
    $this->tokens = explode(' ', $this->dateStr);
    $pos = 0;                         // integer: index to $tokens;
    $endPos = sizeof($this->tokens);  // integer: pointer next over last $tokens;

    if ($endPos == 0) {
      throw new InvalidArgumentException('Invalid: empty date expression');
    }
    $key1 = $this->tokens[$pos];

    // First word of the expression
    switch ($key1) {
      
      // DATE_APPROXIMATED
      case 'ABT': 
        return self::gedBasicDate(++$pos, $endPos) . ' ~abt';
      case 'CAL':
        return self::gedBasicDate(++$pos, $endPos) . ' ~cal';
      case 'EST':
        return self::gedBasicDate(++$pos, $endPos) . ' ~est';
        
      // DATARANGE with single date
      case 'BEF': 
        return self::gedBasicDate(++$pos, $endPos) . ' >';
      case 'AFT': 
        return self::gedBasicDate(++$pos, $endPos) . ' <';
        
      // DATARANGE with 2 dates
      case 'FROM':
        return self::gedRangeDate('FROM', 'TO', $pos, $endPos);
       case 'BETW':
        return self::gedRangeDate('BETW', 'AND', $pos, $endPos);
         
      // DATE_JULN or (invalid) DATE_PHRASE
      default:  
        return self::gedBasicDate($pos, $endPos);
    }
  }

  /**
   * Internal function to parse date range
   * @param type $str1 = FROM/BETW
   * @param type $str2 = TO/AND
   * @return type
   * @throws InvalidArgumentException
   */
  private function gedRangeDate($str1, $str2, $pos, $endPos) {
    $topos = array_search($str2, $this->tokens);
    if ($topos == FALSE) { 
      throw new InvalidArgumentException(
              'Invalid date expression, ' . $str2 . ' expected. "' 
              . $this->dateStr . '"');
    }
    $date1 = self::gedBasicDate($pos + 1, $topos);
    $date2 = self::gedBasicDate($topos + 1, $endPos);
    if ($str1 == 'FROM') {
       return $date1 . ' - ' . $date2;
    } else {
       return $date1 . ' / ' . $date2;
    }
  }

  /**
   * Internal function to parse a gedcom DATE_JULN to db format
   * 
   * Note does not understand the "B.C." part
   * 
   * @staticvar array $monthNum
   * @return string
   * @throws InvalidArgumentException
   * @throws Exception  internal parse error
   */
  private function gedBasicDate($pos, $endPos) {
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

  //echo "<!--debug " 
  //        . implode(array_slice($this->tokens, $pos, $endPos - $pos, true), ':') 
  //        . " -->";

    $state = 0;
    $dd = $mm = '00';
    /*
      state  | 00 [2digit]         | mon [abbr]         | 0000 [4digit]               |
      --------+--------------------+--------------------+----------------------------+
      start 0 | 1, init()+setDay() | 2, Init()+setMon() | 9, init();setYear();end    |
      day   1 |                    | 2, setMon()        |                            |
      month 2 |                    |                    | 9, setYear();end           |
      --------+--------------------+--------------------+----------------------------+
     */
    while ($pos < $endPos) {
      $a = $this->tokens[$pos];
      $pos++;
      switch ($state) {
        case 0: // start
          if (is_numeric($a)) {
            if (strlen($a) == 4) {
              // 4digit: only year given
              if ($pos == $endPos) {
                return $this->dbDate($a, '00', '00');
              } else {
                throw new InvalidArgumentException(
                  "garbage after valid date in \"$this->dateStr\"");
              }
            } else {
              // 2digit: set day
              $dd = $a;
              if ( $dd > 31 ) {
                  throw new InvalidArgumentException(
                    "invalid day value \"$dd\" in \"$this->dateStr\"");
              }
              switch (strlen($dd)) {
                case 1: $dd = '0' . $dd;
                  break;
                case 2: break;
                default:
                  throw new InvalidArgumentException(
                    "invalid day value \"$this->dateStr\"");
              }
              $state = 1;
              break;
            }
          }
          // Not a number: initial month expexted
          if (strlen($a) != 3) {
            throw new InvalidArgumentException(
              "invalid date value \"$this->dateStr\"");
          }
          $mm = isset($monthNum[$a]) ? $monthNum[$a] : '00';
          //echo "<!-- mm=$a -> $mm -->";
          $state = 2;
          break;
        case 1: // day passed, month expected
          $mm = isset($monthNum[$a]) ? $monthNum[$a] : '00';
          $state = 2;
          break;
        case 2: // month passed, year expected
          if (strlen($a) != 4) {
            throw new InvalidArgumentException(
              "invalid year value \"$this->dateStr\"");
          }
          if ($pos == $endPos) {
            return $this->dbDate($a, $mm, $dd);
          } else {
            throw new InvalidArgumentException(
              "garbage after valid date in \"$this->dateStr\"");
          }
      } // switch
    } // while

    throw new Exception(
      "Fatal GedDateParser state=$state, pos=$pos-$endPos \"$this->dateStr\"");
  }

  /**
   * A function to build a basic DATE_JULN type db format date
   * @param type $yyyy
   * @param type $mm
   * @param type $dd
   * @return type
   */
  function dbDate($yyyy, $mm, $dd) {
    return $yyyy . self::DELIM . $mm . self::DELIM . $dd;
  }

// gedBasicDate
}
