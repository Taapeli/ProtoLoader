<?php

  echo '<table class="tulos">';
  echo '<tr><th>id</th><th>Etunimet</th><th>Sukunimi</th>' .
       '<th>Myöh. sukunimi</th><th>Syntymäaika</th><th>Syntymäpaikka</th></tr>';
 
  for ($i=0; $i<sizeof($individ); $i++) {
    echo "<tr><td><a href='readIndividData.php?id=" . $individ[$i]->getId() . "'>" 
         . $individ[$i]->getId() . "</a></td>";
    echo "<td> " . $individ[$i]->getFirstname() .
         "</td><td> " . $individ[$i]->getLastname() .
         "</td><td> " . $individ[$i]->getLaternames() . 
         "</td><td> " . $individ[$i]->getBirthdate() .
         "</td><td> " . $individ[$i]->getBirthplace() .
         "</td></tr>";
  }
  echo "</table><p>&nbsp;</p>";

?>
