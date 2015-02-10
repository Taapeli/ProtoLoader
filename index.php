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
<h2>Taapelin tarkistusohjelmat</h2>
<ul>
<li>Gedcom-tiedoston tarkistuksessa l&ouml;ydetyt 
   <a href="listToDoData.php" target="_blank">korjausta vaativat seikat</a></li>
<li>Lista henkilöist&auml;, joilla ei ole 
   <a href="listNotSetBirthdays.php" target="_blank">syntym&auml;aikaa</a> tai 
   <a href="listNoHiskiLinks.php" target="_blank">Hiski-linkki&auml;</a></li>
<li>Lista henkilöist&auml;, joilla on 
   <a href="listMayBeSame.php" target="_blank">sama syntym&auml;aika sek&auml;
      sama etu- ja sukunimi</a></li>
</ul>
<h2>Taapelin korjausohjelmat</h2>
<ul>
<li>Yhdist&auml; sellaiset henkil&ouml;t, joilla on  
   <a href="connectSameBirthDates.php" target="_blank">sama syntym&auml;aika</a> tai 
   <a href="connectSameNames.php" target="_blank">samat etu- ja sukunimet</a></li>
<li>Katkaise mahdollinen yhteys henkil&ouml;ilt&auml;, joilla on  
   <a href="disconnectSameBirthDates.php" target="_blank">sama syntym&auml;aika</a> tai
   <a href="disconnectSameNames.php" target="_blank">samat etu- ja sukunimet</a></li>
</ul>
</div>

<div class="form">
<form action="listBirths.php" method="post" enctype="multipart/form-data">
<h2>Haku syntym&auml;ajalla</h2>
<p>Anna haettava syntym&auml;aika muodossa "1837-09-02"</p>
<p><span class="tit">P&auml;iv&auml;m&auml;&auml;r&auml;:</span> 
<input type="text" name="birth" required="required" />
<input class="subm" type="submit" value="Etsi" /></p>
</form>
</div>

<div class="form">
<form action="listNamesWithPort7473.php" method="post" enctype="multipart/form-data">
<h2>Nimihaku</h2>
<p>Anna haettava sukunimi sukunimi-kentt&auml;&auml;n tai sukunimen alku 
vapaahaku-kentt&auml;&auml;n (k&auml;ytt&auml;en porttia: 7473). </p>
<p>Haku tapahtuu Sukunimen mukaan, jos annettu, muuten haetaan 
vapaahaku-kentt&auml;n mukaan.</p>
<p><span class="tit">Sukunimi:</span> 
<input type="text" name="name"/> (esim. Saarikunnas)</p>
<p><span class="tit">Vapaahaku:</span> 
<input type="text" name="wildcard"/> (esim. Saarik)
<input class="subm" type="submit" value="Etsi"/></p>
</form>
</div>

<div class="form">
<form action="listNames.php" method="post" enctype="multipart/form-data">
<h2>Nimihaku</h2>
<p>Anna haettava sukunimi sukunimi-kentt&auml;&auml;n tai sukunimen alku 
vapaahaku-kentt&auml;&auml;n. </p>
<p>Haku tapahtuu Sukunimen mukaan, jos annettu, muuten haetaan 
vapaahaku-kentt&auml;n mukaan.</p>
<p><span class="tit">Sukunimi:</span> 
<input type="text" name="name"/> (esim. Saarikunnas)</p>
<p><span class="tit">Vapaahaku:</span> 
<input type="text" name="wildcard"/> (esim. Saarik)
<input class="subm" type="submit" value="Etsi"/></p>
</form>
</div>

<div class="form">
<h2>Lataa gedcom-tiedosto</h2>
<p>Sy&ouml;tteen&auml; annettu gedcom-tiedosto luetaan kantaan
   siell&auml; jo olevien tietojen lis&auml;ksi.</p>
<!-- div class="indent">
	<h3>a) K&auml;ytet&auml;&auml;n neo4jphp- ja cypher-komentoja</h3>
	<form action="gedLoader.php" method="post" enctype="multipart/form-data">
	<p><span class="tit">Sy&ouml;te:</span> 
	<input type="file" name="image" required="required" />
	<input class="subm" type="submit" value="Lataa" /></p>
	</form>
</div>

<div class="indent">
	<form action="gedLoader2.php" method="post" enctype="multipart/form-data">
	<h3>b) K&auml;ytet&auml;&auml;n vain cypher-komentoja</h3>
	<p><span class="tit">Sy&ouml;te:</span> 
	<input type="file" name="image" required="required" />
	<input class="subm" type="submit" value="Lataa" /></p>
	</form>
</div -->

<div class="indent">
	<form action="gedLoaderWithLabel.php" method="post" enctype="multipart/form-data">
	<h3>a) Tallennetaan k&auml;ytt&auml;j&auml;-labelin kanssa</h3>
	<p><span class="tit">Sy&ouml;te:</span> 
	<input type="file" name="image" required="required" />
	<input class="subm" type="submit" value="Lataa" /></p>
	</form>
</div>

<!-- div class="indent">
	<form action="gedLoaderWithPort7473.php" method="post" enctype="multipart/form-data">
	<h3>d) Tallennetaan (k&auml;ytt&auml;en porttia: 7473) myös k&auml;ytt&auml;j&auml;-labelin kanssa</h3>
	<p><span class="tit">Sy&ouml;te:</span> 
	<input type="file" name="image" required="required" />
	<input class="subm" type="submit" value="Lataa" /></p>
	</form>
</div -->
</div>

</body>
</html>
