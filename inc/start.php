<?php ?>
<div id="top">
    <span>&nbsp;<img src="/images/Vaakuna_v65px.png" alt="" /></span>
    <span id="toptitle">
        Taapeli &mdash; suomalaisten sukutietojen demo-ohjelma
    </span>
    <div id="login">
        <?php
        if (isset($_SESSION['userid'])) { // Logged in
          echo "Käyttäjä " . $_SESSION['userid']
          . " &mdash; <a href='/inc/logout.php'>kirjaudu ulos</a>";
        } else { // Do login
          ?>
          <form action="inc/setUserid.php" method="post" enctype="multipart/form-data">
              <input type="text" name="userid" />
              <input type="submit" name="button" value="Kirjaudu"  />
          </form>           
        <?php } ?>
    </div>
</div>

<div id="wrap">
    <div id="menu">
        <ul class="menu">
            <?php if (isset($_SESSION['taapeli'])) { ?>
              <li>Tietojen tarkistus
                  <ul class="menu">
                      <li><a href="listToDoData.php">Löydetty korjattavaa</a></li>
                      <li><a href="listNotSetBirthdays.php">Ei syntymäaikaa</a></li>
                      <li><a href="listNoHiskiLinks.php">Ei Hiski-linkkiä</a></li>
                      <li>Keskeneräinen<a href="listMayBeSame.php">
                              Sama syntymäaika, etu- ja sukunimi</a></li>
                  </ul>
              </li>
              <li>Henkilöiden yhdistely
                  <ul class="menu">
                      <li><a href="connectSameBirthDates.php">Sama syntymäaika</a></li> 
                      <li><a href="connectSameNames.php">Samat etu- ja sukunimet</a></li>
                  </ul></li>
              <li>Katkaise henkilöyhteys
                  <ul class="menu">
                      <li><a href="disconnectSameBirthDates.php">Sama syntymäaika</a></li>
                      <li><a href="disconnectSameNames.php">samat etu- ja sukunimet</a></li>
                      <li>Keskeneräinen   
                          <a href="compareTwoFamilyForm.php">yhdistelyehdotus</a></li>
                  </ul></li>
            <?php } ?>
            <li>Haku syntymäajalla <i>vvvv.kk.pp</i>
                <form action="listBirths.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="birth" />
                    <input type="submit" value="Etsi" />
                </form></li>
            <li>Haku koko sukunimellä
                <form action="listNames.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="name"/>
                    <input type="submit" value="Etsi"/>
                </form></li>
            <li>Haku sukunimen alkuosalla
                <form action="listNames.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="wildcard"/>
                    <input type="submit" value="Etsi"/>
                </form></li>

            <li>Lataa gedcom-tiedosto
                <form action="gedLoaderWithLabel.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" />
                    <input type="submit" value="Lataa" />
                </form>
            </li>
        </ul>
        <div class="note">Gedcom-tiedoston latauksessa uudet tiedot luetaan kantaan
            siellä jo olevien tietojen lisäksi.</div>
    </div>
    <div id="content">
