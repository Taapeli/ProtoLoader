<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Taapelin prototyyppi</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <style>
        div.top {
            background-color: #82ac92;
        }
    </style>
</head>

<body>
    <?php ?>
    <div class="top">
        <span>&nbsp;<img src="images/Vaakuna_v65px.png" alt="" /></span>
        <span class="toptitle">
            Taapeli &mdash; suomalaisten sukutietojen demo-ohjelma
        </span>
    </div>

    <div class="menu">
        <ul>
            <li>Tietojen tarkistus
                <ul>
                    <li><a href="listToDoData.php">Löydetty korjattavaa</a></li>
                    <li><a href="listNotSetBirthdays.php">Ei syntymäaikaa</a></li>
                    <li><a href="listNoHiskiLinks.php">Ei Hiski-linkkiä</a></li>
                    <li><a href="listMayBeSame.php">Sama syntymäaika, etu- ja sukunimi</a></li>
                </ul>
            </li>
            <li>Henkilöiden yhdistely
                <ul>
                    <li><a href="connectSameBirthDates.php">Sama syntymäaika</a></li> 
                    <li><a href="connectSameNames.php">Samat etu- ja sukunimet</a></li>
                </ul></li>
            <li>Katkaise henkilöyhteys
                <ul><li><a href="disconnectSameBirthDates.php">Sama syntymäaika</a></li><li>
                        <a href="disconnectSameNames.php">samat etu- ja sukunimet</a></li>
                    <li>Keskeneräinen   
                        <a href="compareTwoFamilyForm.php">yhdistelyehdotus</a></li>
                </ul></li>

            <li><form action="listBirths.php" method="post" enctype="multipart/form-data">
                    Haku syntymäajalla 
                    <input type="text" name="birth" />
                    <input class="subm" type="submit" value="Etsi" />
                </form></li>
            <li><form action="listNames.php" method="post" enctype="multipart/form-data">
                    Haku koko sukunimellä 
                    <input type="text" name="name"/>
                    <input class="subm" type="submit" value="Etsi"/>
                </form></li>
            <li><form action="listNames.php" method="post" enctype="multipart/form-data">
                    Haku nimen alkuosalla 
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
