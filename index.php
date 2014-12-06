<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Taapelin prototyyppi</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
<div class="goback"><a href="index.php">Paluu</a></div>

<div class="top">
<h1>Taapelin prototyyppi</h1>
</div>
<div class="form">
<h2>Taapelin tarkistus ohjelmat</h2>
<p>Gedcom-tiedoston tarkistuksessa l&ouml;ydetyt:</p>
<p><a href="listToDoData.php" target="_blank">Korjausta vaativat seikat</a></p>
<p>Listaa sellaiset henkil&ouml;t, joilla ei ole:</p> 
<p><a href="listNotSetBirthdays.php" target="_blank">Syntym&auml;aikaa</a></p>
<p><a href="listNoHiskiLinks.php" target="_blank">Hiski-linkki&auml;</a></p>
<div class="form">
<form action="listBirths.php" method="POST" enctype="multipart/form-data"></p>
<h2>Haku syntymäajalla</h2>
<p>Anna haettava syntym&auml;aika muodossa "1837.09.02"</p>
<p><span class="tit">Sy&ouml;te:</span> 
<input type="text" name="birth" required/>
<input class="subm" type="submit"/></p>
</form>
</div>

<div class="form">
<form action="listNames.php" method="POST" enctype="multipart/form-data"></p>
<h2>Nimihaku</h2>
<p>Anna haettava sukunimi sukunimi-kentt&auml;&auml;n tai sukunimen alku 
vapaahaku-kentt&auml;&auml;n. </p>
<p>Haku tapahtuu Sukunimen mukaan, jos annettu, muuten haetaan 
vapaahaku-kentt&auml;n mukaan.</p>
<p><span class="tit">Sukunimi:</span> 
<input type="text" name="name"/> (esim. Saarikunnas)</p>
<p><span class="tit">Vapaahaku:</span> 
<input type="text" name="wildcard"/> (esim. Saarik)
<input class="subm" type="submit"/></p>
</form>
</div>

<div class="form">
<form action="gedLoader.php" method="POST" enctype="multipart/form-data"></p>
<h2>Lataa gedcom-tiedosto</h2>
<p>Sy&ouml;tteen&auml; annettu gedcom-tiedosto luetaan kantaan 
siellä jo olevien tietojen lisäksi.</p>
<p><span class="tit">Sy&ouml;te:</span> 
<input type="file" name="image" required/>
<input class="subm" type="submit"/></p>
</form>
</div>

</body>
</html>
