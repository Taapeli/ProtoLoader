<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapelin prototyyppi</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>
        <div class="goback"><a href="index.php">Paluu</a></div>

        <div class="top">
            <h1>Taapelin prototyyppi</h1>
        </div>

        <div class="form">
            <h2>Taapeliin kirjautuminen</h2>
            <form action="inc/setUserid.php" method="post" enctype="multipart/form-data">
                <p>Anna k&auml;ytt&auml;j&auml;tunnus (user1234):</p>
                <p><span class="tit">Tunnus</span> 
                    <input type="text" name="userid" required="required" />
                    <input class="subm" type="submit" value="Kirjaudu" /></p>
            </form>
        </div>

        <div class="form">
            <h2>Taapelin tarkistusohjelmat</h2>
            <ul>
                <li>Gedcom-tiedoston tarkistuksessa löydetyt 
                    <a href="listToDoData.php" target="muokkaus">korjausta vaativat seikat</a></li>
                <li>Lista henkilöistä, joilla ei ole 
                    <a href="listNotSetBirthdays.php" target="muokkaus">syntymäaikaa</a> tai 
                    <a href="listNoHiskiLinks.php" target="muokkaus">Hiski-linkkiä</a></li>
                <li>Lista henkilöistä, joilla on 
                    <a href="listMayBeSame.php" target="muokkaus">sama syntymäaika sekä
                        sama etu- ja sukunimi</a></li>
            </ul>
            <h2>Taapelin korjausohjelmat</h2>
            <ul>
                <li>Yhdistä sellaiset henkilöt, joilla on  
                    <a href="connectSameBirthDates.php" target="muokkaus">sama syntymäaika</a> tai 
                    <a href="connectSameNames.php" target="muokkaus">samat etu- ja sukunimet</a></li>
                <li>Katkaise mahdollinen yhteys henkilöiltä, joilla on  
                    <a href="disconnectSameBirthDates.php" target="muokkaus">sama syntymäaika</a> tai
                    <a href="disconnectSameNames.php" target="muokkaus">samat etu- ja sukunimet</a></li>
                <li>Keskeneräinen   
                    <a href="compareTwoFamilyForm.php" target="muokkaus">yhdistelyehdotuslomake</a> tai
            </ul>
        </div>

        <div class="form">
            <form action="listBirths.php" method="post" enctype="multipart/form-data">
                <h2>Haku syntymäajalla</h2>
                <p>Anna haettava syntymäaika muodossa "1837-09-02"</p>
                <p><span class="tit">Päivämäärä:</span> 
                    <input type="text" name="birth" required="required" />
                    <input class="subm" type="submit" value="Etsi" /></p>
            </form>
        </div>

        <div class="form">
            <form action="listNames.php" method="post" enctype="multipart/form-data">
                <h2>Nimihaku</h2>
                <p>Anna haettava sukunimi sukunimi-kenttään tai sukunimen alku 
                    vapaahaku-kenttään. </p>
                <p>Haku tapahtuu Sukunimen mukaan, jos annettu, muuten haetaan 
                    vapaahaku-kenttän mukaan.</p>
                <p><span class="tit">Sukunimi:</span> 
                    <input type="text" name="name"/> (esim. Saarikunnas)</p>
                <p><span class="tit">Vapaahaku:</span> 
                    <input type="text" name="wildcard"/> (esim. Saarik)
                    <input class="subm" type="submit" value="Etsi"/></p>
            </form>
        </div>

        <div class="form">
            <h2>Lataa gedcom-tiedosto</h2>
            <p>Syötteenä annettu gedcom-tiedosto luetaan kantaan
                siellä jo olevien tietojen lisäksi.</p>
            <!-- div class="indent">
                    <h3>a) Käytetään neo4jphp- ja cypher-komentoja</h3>
                    <form action="gedLoader.php" method="post" enctype="multipart/form-data">
                    <p><span class="tit">Syöte:</span> 
                    <input type="file" name="image" required="required" />
                    <input class="subm" type="submit" value="Lataa" /></p>
                    </form>
            </div>
            
            <div class="indent">
                    <form action="gedLoader2.php" method="post" enctype="multipart/form-data">
                    <h3>b) Käytetään vain cypher-komentoja</h3>
                    <p><span class="tit">Syöte:</span> 
                    <input type="file" name="image" required="required" />
                    <input class="subm" type="submit" value="Lataa" /></p>
                    </form>
            </div -->

            <div class="indent">
                <form action="gedLoaderWithLabel.php" method="post" enctype="multipart/form-data">
                    <h3>Tallennetaan käyttäjä-labelin kanssa</h3>
                    <p><span class="tit">Syöte:</span> 
                        <input type="file" name="image" required="required" />
                        <input class="subm" type="submit" value="Lataa" /></p>
                </form>
            </div>

            <!-- div class="indent">
                    <form action="gedLoaderWithPort7473.php" method="post" enctype="multipart/form-data">
                    <h3>d) Tallennetaan (käyttäen porttia: 7473) myös käyttäjä-labelin kanssa</h3>
                    <p><span class="tit">Syöte:</span> 
                    <input type="file" name="image" required="required" />
                    <input class="subm" type="submit" value="Lataa" /></p>
                    </form>
            </div -->
        </div>

    </body>
</html>
