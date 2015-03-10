<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * Description of GedDate
 *
 * @author jm
 */
class GedDateParser {

  const DELIM = '-';    // Delimiter in the db

  // For parsing gedcom DATE_VALUE

  private $dateStr;     // string: original gedcom line
  private $tokens;      // array: gedcom input line
  private $pos;         // integer: index to $tokens;
  private $endPos;      // integer: pointer to last $tokens;

  /**
   * Gedcom DATE_VALUE to db format  -  TESTING NEW FUNC
   * 
   * @param type $gedDate
   * @return type
   * @throws InvalidArgumentException
   * @throws Exception  internal parse error
   */
  public function fromGed($gedDate) {
    $this->dateStr = trim($gedDate);
    $this->tokens = explode(' ', $this->dateStr);
    $this->pos = 0;
    $this->endPos = sizeof($this->tokens) - 1;
    return self::gedDateValue();
  }

  private function gedDateValue() {
    /*
      state   | 0 [digits]    | FROM          | BET           | TO            | AND | BEF/AFT     | end |
      --------+---------------+---------------+---------------+---------------+-----+-------------+-----+
      start 0 | 1,basicDate();| 2,stPeriod1() | 3,stRange1()  | 4,stRange2()  |     | 4,setAppr() |     |
      end   1 |               |               |               |               |     |             | end |
      from  2 | 5,basicDate() |               |               |               |     |             |     |
      betw  3 | 6,basicDate() |               |               |               |     |             |     |
      last  4 | 1,basicDate() |               |               |               |     |             |     |
      -to   5 |               |               |               | 4             |     |             | end |
      -and  6 |               |               |               |               | 4   |             |     |
     */

    if ($this->endPos == 0) {
      return "";  // Empty date
    }
    //if (is_numeric($this->tokens[$this->pos])) {
    return self::gedBasicDate();
    //}
    //echo "<em>Error DateConv: invalid date value \"$this->dateStr\"</em>";
    //return;  // Unparseable
  }

  /**
   * gedcom basic DATE to db format
   * 
   * @staticvar array $monthNum
   * @return string
   * @throws InvalidArgumentException
   * @throws Exception  internal parse error
   */
  private function gedBasicDate() {
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

    $state = 0;
    $dd = $mm = "00";
    $yyyy = "";
    /*
      state  | 00 [2digit]         | mon [abbr]         | 0000 [4digit]               |
      --------+--------------------+--------------------+----------------------------+
      start 0 | 1, init()+setDay() | 2, Init()+setMon() | 9, init();setYear();end    |
      day   1 |                    | 2, setMon()        |                            |
      month 2 |                    |                    | 9, setYear();end           |
      --------+--------------------+--------------------+----------------------------+
     */
    while ($this->pos <= $this->endPos) {
      $a = $this->tokens[$this->pos];
      $this->pos++;
      switch ($state) {
        case 0: // start
          if (is_numeric($a)) {
            if (strlen($a) == 4) {
              // 4digit: only year given
              return $this->dbDate($a, '00', '00');
            } else {
              // 2digit: set day
              $dd = $a;
              if ( $dd > 30 ) {
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
              "invalid month value \"$this->dateStr\"");
          }
          $mm = isset($monthNum[$a]) ? $monthNum[$a] : '00';
          echo "<!-- mm=$a -> $mm -->";
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
          return $this->dbDate($a, $mm, $dd);
      } // switch
    } // while

    throw new Exception(
      "Fatal GedDate state=$state, pos=$this->pos \"$this->dateStr\"");
  }

  function dbDate($yyyy, $mm, $dd) {
    return $yyyy . self::DELIM . $mm . self::DELIM . $dd;
  }

// gedBasicDate
}
