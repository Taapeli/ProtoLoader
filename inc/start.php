    <?php ?>
    <div id="top">
        <span>&nbsp;<img src="images/Vaakuna_v65px.png" alt="" /></span>
        <span class="toptitle">
            Taapeli &mdash; suomalaisten sukutietojen demo-ohjelma
        </span>
    </div>

    <div id="wrap">
    <div id="menu">
        <ul class="menu">
            <li>Tietojen tarkistus
                <ul class="menu">
                    <li><a href="listToDoData.php">Löydetty korjattavaa</a></li>
                    <li><a href="listNotSetBirthdays.php">Ei syntymäaikaa</a></li>
                    <li><a href="listNoHiskiLinks.php">Ei Hiski-linkkiä</a></li>
                    <li><a href="listMayBeSame.php">Sama syntymäaika, etu- ja sukunimi</a></li>
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

            <li>Haku syntymäajalla
                <form action="listBirths.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="birth" />
                    <input class="subm" type="submit" value="Etsi" />
                </form></li>
            <li>Haku koko sukunimellä
                <form action="listNames.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="name"/>
                    <input class="subm" type="submit" value="Etsi"/>
                </form></li>
            <li>Haku sukunimen alkuosalla
                <form action="listNames.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="wildcard"/>
                    <input class="subm" type="submit" value="Etsi"/>
                </form></li>

            <li>Lataa gedcom-tiedosto
                <form action="gedLoaderWithLabel.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" />
                    <input class="subm" type="submit" value="Lataa" />
                </form>
            </li>
        </ul>
        <div class="note">Gedcom-tiedoston latauksessa tiedot luetaan kantaan
            siellä jo olevien tietojen lisäksi.</div>
    </div>
