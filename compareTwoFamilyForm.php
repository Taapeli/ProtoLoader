<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
        <?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Henkilön sulauttaminen Taapeli-kantaan</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<?php

  $etunimi_tarjokas = "Johan Johanpoika1";
  $etunimi_kannassa = "Johan Johanpoika2";

  $sukunimi_tarjokas = "Sihvola1";
  $sukunimi_kannassa = "Sihvola2";

  $saika_tarjokas = "1888.03.01";
  $saika_kannassa = "1888.11.01";

  $spaikka_tarjokas = "Porvoo";
  $spaikka_kannassa = "Borgå";

  $kaika_tarjokas = "1900.03.31";
  $kaika_kannassa = "1900.05.01";

  $kpaikka_tarjokas = "Hanko";
  $kpaikka_kannassa = "Hangö";
?>
<script type="text/javascript">
  window.onload = function() {

  }

  function change_etunimi(rad) {
    var val_etunimi_tarjokas = [];
    var val_etunimi_kannassa = [];

    val_etunimi_tarjokas.push("<?php echo $etunimi_tarjokas; ?>");
    val_etunimi_kannassa.push("<?php echo $etunimi_kannassa; ?>");

    var val=rad.value;
    var choise1 = "etunimi_tarjokas";
    var choise2 = "etunimi_kannassa";
    if (val == choise1) {
      document.getElementById('etunimi_tulos').innerHTML = val_etunimi_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('etunimi_tulos').innerHTML = val_etunimi_kannassa[0];
    }
  }

  function change_sukunimi(rad) {
    var val_sukunimi_tarjokas = [];
    var val_sukunimi_kannassa = [];

    val_sukunimi_tarjokas.push("<?php echo $sukunimi_tarjokas; ?>");
    val_sukunimi_kannassa.push("<?php echo $sukunimi_kannassa; ?>");

    var val=rad.value;
    var choise1 = "sukunimi_tarjokas";
    var choise2 = "sukunimi_kannassa";
    if (val == choise1) {
      document.getElementById('sukunimi_tulos').innerHTML = val_sukunimi_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('sukunimi_tulos').innerHTML = val_sukunimi_kannassa[0];
    }
  }

  function change_saika(rad) {
    var val_saika_tarjokas = [];
    var val_saika_kannassa = [];

    val_saika_tarjokas.push("<?php echo $saika_tarjokas; ?>");
    val_saika_kannassa.push("<?php echo $saika_kannassa; ?>");

    var val=rad.value;
    var choise1 = "saika_tarjokas";
    var choise2 = "saika_kannassa";
    if (val == choise1) {
      document.getElementById('saika_tulos').innerHTML = val_saika_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('saika_tulos').innerHTML = val_saika_kannassa[0];
    }
  }

  function change_spaikka(rad) {
    var val_spaikka_tarjokas = [];
    var val_spaikka_kannassa = [];

    val_spaikka_tarjokas.push("<?php echo $spaikka_tarjokas; ?>");
    val_spaikka_kannassa.push("<?php echo $spaikka_kannassa; ?>");

    var val=rad.value;
    var choise1 = "spaikka_tarjokas";
    var choise2 = "spaikka_kannassa";
    if (val == choise1) {
      document.getElementById('spaikka_tulos').innerHTML = val_spaikka_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('spaikka_tulos').innerHTML = val_spaikka_kannassa[0];
    }
  }

  function change_kaika(rad) {
    var val_kaika_tarjokas = [];
    var val_kaika_kannassa = [];

    val_kaika_tarjokas.push("<?php echo $kaika_tarjokas; ?>");
    val_kaika_kannassa.push("<?php echo $kaika_kannassa; ?>");

    var val=rad.value;
    var choise1 = "kaika_tarjokas";
    var choise2 = "kaika_kannassa";
    if (val == choise1) {
      document.getElementById('kaika_tulos').innerHTML = val_kaika_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('kaika_tulos').innerHTML = val_kaika_kannassa[0];
    }
  }

  function change_kpaikka(rad) {
    var val_kpaikka_tarjokas = [];
    var val_kpaikka_kannassa = [];

    val_kpaikka_tarjokas.push("<?php echo $kpaikka_tarjokas; ?>");
    val_kpaikka_kannassa.push("<?php echo $kpaikka_kannassa; ?>");

    var val=rad.value;
    var choise1 = "kpaikka_tarjokas";
    var choise2 = "kpaikka_kannassa";
    if (val == choise1) {
      document.getElementById('kpaikka_tulos').innerHTML = val_kpaikka_tarjokas[0];
    }
    else if (val== choise2) {
      document.getElementById('kpaikka_tulos').innerHTML = val_kpaikka_kannassa[0];
    }
  }

</script>
</head>
<body>

<?php
  include 'inc/checkUserid.php';
  include "inc/start.php";
  include "inc/dbconnect.php";
  
        /*
         * -- Content page starts here -->
         */
  
  echo '<h1>Verrataan kahta henkilöä toisiinsa</h1>';

  echo "<table class='tulos'>";
  echo "<tr><th>Tietojen valinta</th><th>Tarjokas</th><th>Kannassa</th><th>Yhdistelyn tulos</th></tr>";
  echo "<tr><td>Henkilö</td><td>id=I0127</td><td>id=DRG254378</td><td>id=DRG254378</td></tr>";
  echo "<tr><td>etunimi</td>
          <td><input type='radio' name='etunimi' id='etunimi_tarjokas' value='etunimi_tarjokas'
               onclick='change_etunimi(this)' />
              <label for='etunimi_tarjokas'>" .  $etunimi_tarjokas . "</td>
          <td><input type='radio' name='etunimi' id='etunimi_kannassa' value='etunimi_kannassa' 
               onclick='change_etunimi(this)' />
              <label for='etunimi_kannassa'>" .  $etunimi_kannassa . "</td>
          <td><div id='etunimi_tulos'> </div></td>
        </tr>";

  echo "<tr><td>sukunimi</td>
          <td><input type='radio' name='sukunimi' id='sukunimi_tarjokas' value='sukunimi_tarjokas' 
               onclick='change_sukunimi(this)' />
              <label for='sukunimi_tarjokas'>" .  $sukunimi_tarjokas . "</td>
          <td><input type='radio' name='sukunimi' id='sukunimi_kannassa' value='sukunimi_kannassa' 
               onclick='change_sukunimi(this)' />
              <label for='sukunimi_kannassa'>" .  $sukunimi_kannassa . "</td>
          <td><div id='sukunimi_tulos'> </div></td>
        </tr>";

  echo "<tr><td colspan='4'>Henkilön tapahtumat</td></tr>";
  echo "<tr><td>syntynyt</td>
          <td><input type='radio' name='syntyma_aika' id='saika_tarjokas' value='saika_tarjokas'
               onclick='change_saika(this)' />
              <label for='saika_tarjokas'>" .  $saika_tarjokas . "</td>
          <td><input type='radio' name='syntyma_aika' id='saika_kannassa' value='saika_kannassa' 
               onclick='change_saika(this)' />
              <label for='saika_kannassa'>" .  $saika_kannassa . "</td>
          <td><div id='saika_tulos'> </div></td>
        </tr>";

  echo "<tr><td> </td>
          <td><input type='radio' name='syntyma_paikka' id='spaikka_tarjokas' value='spaikka_tarjokas'
               onclick='change_spaikka(this)' />
              <label for='spaikka_tarjokas'>" .  $spaikka_tarjokas . "</td>
          <td><input type='radio' name='syntyma_paikka' id='spaikka_kannassa' value='spaikka_kannassa' 
               onclick='change_spaikka(this)' />
              <label for='spaikka_kannassa'>" .  $spaikka_kannassa . "</td>
          <td><div id='spaikka_tulos'> </div></td>
        </tr>";

  echo "<tr><td>kuollut</td>
          <td><input type='radio' name='kuolin_aika' id='kaika_tarjokas' value='kaika_tarjokas' 
               onclick='change_kaika(this)' />
              <label for='kaika_tarjokas'>" .  $kaika_tarjokas . "</td>
          <td><input type='radio' name='kuolin_aika' id='kaika_kannassa' value='kaika_kannassa' 
               onclick='change_kaika(this)' />
              <label for='kaika_kannassa'>" .  $kaika_kannassa . "</td>
          <td><div id='kaika_tulos'> </div></td>
        </tr>";

  echo "<tr><td> </td>
          <td><input type='radio' name='kuolin_paikka' id='kpaikka_tarjokas' value='kpaikka_tarjokas' 
               onclick='change_kpaikka(this)' />
              <label for='kpaikka_tarjokas'>" .  $kpaikka_tarjokas . "</td>
          <td><input type='radio' name='kuolin_paikka' id='kpaikka_kannassa' value='kpaikka_kannassa' 
               onclick='change_kpaikka(this)' />
              <label for='kpaikka_kannassa'>" .  $kpaikka_kannassa . "</td>
          <td><div id='kpaikka_tulos'> </div></td>
        </tr>";

  echo "</table>";

  /*
   *  -- End of content page -->
   */

include "inc/stop.php";
