<?php

/*
 * Taapeli Project by Suomen Sukututkimusseura ry
 * Creating a comprehensive genealogical database for Finland
 */

/**
 * GedLoader is used for importing gedcom data to Taapeli ehdokaskanta.
 * 
 *
 * @author jm
 */

require_once 'GedDateParser.php';

class GedLoader {

  static private $cnt_lines = 0;          // Input lines
  static private $cnt_lines_skipped = 0;  // Skipped lines
  static private $cnt_persons = 0;        // Persons found
  static private $cnt_families = 0;       // Families found
  static private $cnt_sources = 0;        // Source found created (?)
  static private $cnt_repositories = 0;   // Repositories found
  
  static private $user;
  static private $messages = [];

  /**
   * Initiate a file loader for this user 
   * @param string $user_id
   */
  public function __construct($user_id) {
    $this->user = $user_id;
  }

  /**
   * Here is the list of gedcom tags which are on used when readint to Taapeli 
   * and passed silently.
   * 
   * Source <a href="https://docs.google.com/document/d/16k6f0awUa81-zw7OzUsI3vNxTsRcAlXzM6-jppESqhM/edit#heading=h.9p50iy4ri9py"
   * >Gedcomin viralliset tagit</a>
   * 
   * Date 3.2.2015
   * 
   * @param string $tag A gedcom tag
   * @return boolean true, if this tag will not be processed
   */
  private function skipGedcomTag($tag) {

    static $passTag = [
        'ADDR', 'ADR1', 'ADR2', 'ADOP', 'AFN',
        'AGNC', 'ANCI', 'BAPL', 'BAPM', 'BARM',
        'BASM', 'BLES', 'BLOB', 'CAST', 'CHAN',
        'CHAR', 'CHRA', 'CITY', 'CONL',
        'CTRY', 'DESI', 'EMAIL', 'ENDL', 'FAMF',
        'FAX', 'FCOM', 'FILE', 'FONE', 'FORM',
        'GEDC', 'IDNO', 'LATI', 'LEGA', 'LONG',
        'MAP', 'MARB', 'MARC', 'MARL', 'MARS',
        'MEDI', 'NATU', 'NCHI', 'NICK', 'NMR',
        'OBJE', 'ORDI', 'ORDN', 'PHON', 'POST',
        'PROB', 'RFN', 'RIN', 'ROMN', 'SLGC',
        'SLGS', 'SSN', 'STAE', 'TEMP'
    ];
    return (in_array($tag, $passTag));
  }

  /**
   * Remove @ signs from a ged id
   * @param string $id
   * @return string
   */
  private function idtrim($id) {
    return substr(trim($id), 1, -1);
  }

  /**
   * Remove / signs from a name field, if exist
   * @param string $id
   * @return string
   */
  private function nametrim($id) {
    $id_1 = substr(trim($id), 0, 1);
    if ($id_1 == "/") {
      return substr(trim($id), 1, -1);
    } else {
      return $id;
    }
  }

  /**
   * Read a gedcom file to database.
   * @param string $file_tmp name of temporary file on server
   * @param string $user the user name of the owner of data
   * @return array(array(error_messages), array(statistics))
   */
  public function loadFile($file_tmp) {
    //put your code here
    //
    // Remove these
    $this->messages[] = "Warning line 68: Unknown tag 1 GRAD";
    $this->messages[] = "Warning line 69: Unknown tag 2 TYPE";
    
    return array(
        $this->messages,
        array($this->cnt_lines, $this->cnt_lines_skipped,
            $this->cnt_persons, $this->cnt_families,
            $this->cnt_sources, $this->cnt_repositories)
    );
  }

}
