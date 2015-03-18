<!DOCTYPE html PUBLIC "XHTML 1.0 Transitional" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <?php /* php session_start(); */ ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Taapeli - valitse ladattava tiedosto</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <!--
        Taapeli Project by Suomen Sukututkimusseura ry
        Creating a comprehensive genealogical database for Finland
        -->
    </head>
    <body>
        <h1>Lataa gedcom-tiedosto</h1>
        <form action="loadGedFile.php" method="post" enctype="multipart/form-data">
            <p>
            <input type="file" name="image" />
            <input type="submit" value="Lataa" />
            </p>
        </form>

        <p>Gedcom-tiedoston latauksessa uudet tiedot luetaan kantaan
            siellä jo olevien tietojen lisäksi.</p>
        <p></p>
    </body>
</html>
