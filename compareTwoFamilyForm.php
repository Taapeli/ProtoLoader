<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kantaan</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
  window.onload = function() {

  }

  function change_e(rad) {
    var val_etunimi_tarjokas = 'Johan Johanpoika1';
    var val_etunimi_kannassa = 'Johan Johanpoika2';

    var val=rad.value;
    var choise1 = "etarjokas";
    var choise2 = "ekannassa";
    if (val == choise1) {
      document.getElementById('etunimi_tulos').innerHTML = val_etunimi_tarjokas;
    }
    else if (val== choise2) {
      document.getElementById('etunimi_tulos').innerHTML = val_etunimi_kannassa;
    }
  }

  function change_s(rad) {
    var val_sukunimi_tarjokas = 'Sihvola1';
    var val_sukunimi_kannassa = 'Sihvola2';

    var val=rad.value;
    var choise1 = "starjokas";
    var choise2 = "skannassa";
    if (val == choise1) {
      document.getElementById('sukunimi_tulos').innerHTML = val_sukunimi_tarjokas;
    }
    else if (val== choise2) {
      document.getElementById('sukunimi_tulos').innerHTML = val_sukunimi_kannassa;
    }
  }

</script>
</head>
<body>
  <h1>Verrataan kahta henkil&ouml;&auml; toisiinsa</h1>
  <p>Luetaan neo4j-tietokannasta.</p>

<?php

  require_once('nodeClasses');
  
  $tarjokas = new Person();
  $tarjokas.setEtunimi("Johan Johanpoika1");
  $tarjokas.setSukunimi("Sihvola1");
  
  $kannassa = new Person();
  $kannassa.setEtunimi("Johan Johanpoika2");
  $kannassa.setSukunimi("Sihvola2");
  
  echo "<table  cellpadding='2pt' cellspacing='2pt' border='1'>";
  echo "<tr><th>Tietojen valinta<th>Tarjokas<th>Kannassa<th>Yhdistelyn tulos</tr>";
  echo "<tr><td>Henkilö</td><td>id=I0127</td><td>id=DRG254378</td><td>id=DRG254378</td></tr>";
  echo "<tr><td>etunimi</td>
          <td><input type='radio' name='etunimi' id='etarjokas' value='etarjokas'
               onclick='change_e(this)'>
              <label for='etarjokas'>" .  $etunimi_tarjokas . "</td>
          <td><input type='radio' name='etunimi' id='ekannassa' value='ekannassa' 
               onclick='change_e(this)'>
              <label for='etarjokas'>" .  $etunimi_kannassa . "</td>
          <td><div id='etunimi_tulos'>" .  $etunimi_tarjokas . "</div></td>
        </tr>";
  echo "<tr><td>sukunimi</td>
          <td><input type='radio' name='sukunimi' id='starjokas' value='starjokas' 
               onclick='change_s(this)'>
              <label for='etarjokas'>" .  $sukunimi_tarjokas . "</td>
          <td><input type='radio' name='sukunimi' id='skannassa' value='skannassa' 
               onclick='change_s(this)'>
              <label for='etarjokas'>" .  $sukunimi_kannassa . "</td>
          <td><div id='sukunimi_tulos'>" .  $sukunimi_tarjokas . "</div></td>
        </tr>";
  echo "</table>";
?>
</body>
</html>
