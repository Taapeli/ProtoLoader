<?php

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
function skipGedcomTag($tag) {

  static $passTag = [
      'ADDR', 'ADR1', 'ADR2', 'ADOP', 'AFN',
      'AGNC', 'ANCI', 'BAPL', 'BAPM', 'BARM',
      'BASM', 'BLES', 'BLOB', 'CAST', 'CHAN',
      'CHAR',         'CHRA', 'CITY', 'CONL',
      'CTRY', 'DESI', 'EMAIL','ENDL', 'FAMF',
      'FAX',  'FCOM', 'FILE', 'FONE', 'FORM',
      'GEDC', 'IDNO', 'LATI', 'LEGA', 'LONG',
      'MAP',  'MARB', 'MARC', 'MARL', 'MARS',
      'MEDI', 'NATU', 'NCHI', 'NICK', 'NMR',
      'OBJE', 'ORDI', 'ORDN', 'PHON', 'POST',
      'PROB', 'RFN',  'RIN',  'ROMN', 'SLGC',
      'SLGS', 'SSN',  'STAE', 'TEMP'
  ];
  return (in_array($tag, $passTag));
}
