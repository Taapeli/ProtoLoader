<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<title>Taapeli aineiston luku kannasta</title>
<style>
b { color:red }
.form { background-color: #dde; margin-left: auto; margin-right: auto; }
th,td { padding: 5px; }
</style>
</head>

<body>
<h1>Taapeli testiluku</h1>
<p>Luetaan neo4j-tietokannasta.</p>


<form action="listNames.php" method="POST" enctype="multipart/form-data"></p>
<table class="form">
<tr><td>
<h2>Anna haettava sukunimi Sukunimi-kentt&auml;&auml;n tai</h2>
<h2> sukunimen alku Wildcard-kentt&auml;&auml;n</h2>
<h3>Huom! Haku suoritetaan ensisijaisesti Sukunimen mukaan</h3>
<h3>Jos k&auml;yt&auml;t Wildcard-kentt&auml;&auml;, niin j&auml;t&auml; Sukunimi-kentt&auml; tyhj&auml;ksi</h3>
<p>Sukunimi: <input type="text" name="name"/> (esim. Saarikunnas)</p>
<p>Wildcard: <input type="text" name="wildcard"/>(esim. Saarik)</p>
</td><td style="vertical-align: bottom"> 
<input type="submit"/>
</td></tr>
</table>
</form>
</body>
</html>
