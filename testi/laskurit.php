<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lasketaan tyypit</title>
        <link rel="stylesheet" type="text/css" href="../style.css" />
        <style type="text/css">
          div.inline {
              border-width: 0px;
              display: inline-block;
              padding: 25px;
          }
          td { font-size: 9pt; }
        </style>
    </head>
    <body>
        <h1>Aineiston määrälaskenta</h1>
<?php
        $lkm = [];

        // put your code here
        if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
          // Tiedoston käsittelyn muuttujat
          $file_name = $_FILES['image']['name'];
          $file_size = $_FILES['image']['size'];
          $file_tmp = $_FILES['image']['tmp_name'];

          echo '<p><em>Ladataan tiedosto: ' . $file_name
            . ' (size=' . $file_size . ')</em></p>';
          /*
           * Luetaan tiedosto
           */
          $file_handle = fopen($file_tmp, "r");
          echo '<p>Luetaan ';
          while (!feof($file_handle)) {
            $line = fgets($file_handle);
            $n++;
            $a = explode(' ', $line, 4);
            $level = $a[0];
            $id = trim($a[1]);
            $key = trim($a[2]);
            $ch1 = substr($id, 0, 1);
            if ($ch1 == '@' && $key != "") {
              $idlkm["$id"]++;
              $id = $key;
            }
            $lkm["$level $id"] ++;
            //echo " $n";
          } 
          echo "- luettu $n riviä</p>";
          /*
           * Tulostetaan tilasto
           */
          echo '<div class="inline"><h2>Ladatut tyypit</h2>';
          echo '<p>'.  sizeof($lkm) .' erilaista</p>';
          echo '<table class="tulos">';
          ksort($lkm);
          echo "<tr><th>Tyyppi</th><th><div class='right'>Kpl</div></th></tr>\n";
          foreach ($lkm as $x => $x_value) {
            echo "<tr><td>$x</td><td><div class='right'>$x_value</div></td></tr>\n";
          } 
          echo '</table></div>';
          echo '<div class="inline"><h2>Havaittuja avaimia</h2>';
          echo '<p>'.  sizeof($idlkm) .' erilaista</p>';
          echo '<table class="tulos">';
          ksort($idlkm);
          echo "<tr><th>Avain</th><th><div class='right'>Kpl</div></th></tr>\n";
          $c = sizeof($lkm); // Ei tehdä isompaa
          foreach ($idlkm as $x => $x_value) {
            if (--$c == 0) {
              echo "<tr><td>...</td><td> </td></tr>\n";
              break;
            }
            echo "<tr><td>$x</td><td><div class='right'>$x_value</div></td></tr>\n";
          } 
          echo '</table></div>';
        } // is isset
?>
        <form action="" method="POST" enctype="multipart/form-data">
        <h2>Anna ladattava gedcom-tiedosto</h2>
        <p>Syöte: <input type="file" name="image" required/></p>
        <input type="submit" value="Lataa"/>
    </form>
</body>
</html>
