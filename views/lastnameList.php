<?php

  $input_name = htmlspecialchars($_POST['name']);
  echo "<h1>Haku nimellä <i>$input_name</i> </h1>";

  echo '<table class="tulos">';
  echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
       '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  foreach ($individs as $i => $indi) {
    echo "<tr><td><a href='readIndividData.php?id=" . $indi->getId() . "'>" 
         . $indi->getId() . "</a></td>";
    echo "<td> " . $indi->getFirstname() .
         "</td><td> " . $indi->getLastname() .
         "</td><td> " . $indi->getLaternames() . 
         "</td><td> " . $indi->getBirthdate() .
         "</td><td> " . $indi->getBirthplace() .
         "</td></tr>";
  }
  echo "</table><p>&nbsp;</p>";

?>
