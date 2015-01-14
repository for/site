<?php
require '../assets/db2.php';
session_start();

$Genres = "";

$id = Crypt::Decrypt($_GET['id']);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['btnSubmit'])){
        $error = "";

        if ($_POST['txtTitle'] == ""){
            $error .= "You need to have a title!";
        }

        $link = Db::open();

        $stmt = $link -> prepare("UPDATE Movies SET Runtime = ?, Certificate = ?, Description = ?, Title = ?, ReleaseDate = ?, GenreID = ? WHERE MovieID = ?");

        $stmt -> bind_param('issssii', $_POST['txtRuntime'], $_POST['txtCertificate'], $_POST['txtDescription'], $_POST['txtTitle'], $_POST['txtReleaseDate'], $_POST['txtGenre'], $id);

        $stmt -> execute();

        $stmt -> close();

    }
}

$link = Db::open();

$stmt = $link -> prepare("SELECT FileName, Movies.MovieID, Runtime, Certificate, Description, Genre, Title, ReleaseDate, Genres.GenreID FROM Movies LEFT JOIN Genres ON Genres.GenreID = Movies.GenreID LEFT JOIN ArtWork ON ArtWork.MovieID = Movies.MovieID AND ArtWork.CoverArt = 1 WHERE Movies.MovieID = ?");

$stmt -> bind_param('i', $id);

$stmt -> bind_result($FileName, $MovieID, $Runtime, $Certificate, $Description, $Genre, $Title, $ReleaseDate, $MovieGenreID);

$stmt -> execute();

$stmt -> fetch();

$stmt -> close();
//$Genres = "";

$stmt2 = $link -> prepare("SELECT GenreID, Genre FROM Genres");

$stmt2 -> bind_result($GenreID, $Genre);

$stmt2 -> execute();

while($stmt2 -> fetch()){
    if($GenreID == $MovieGenreID){
        $Genres .= "<option value='$GenreID' selected>$Genre</option>";
    }
    else{
        $Genres .= "<option value='$GenreID'>$Genre</option>";
    }
}



?>

<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">
</head>

<body>

    <form method="POST">

        <div class="container-fluid">

            <div class="row-fluid">

                <div class="col-xs-5 col-xs-offset-1">
                <label for="txtTitle">Title</label>
                <input type="text" class="form-control" name="txtTitle" id="txtTitle" placeholder="Title" value="<?=$Title?>">
                </div>

                <div class="col-xs-5">
                <label for="txtDescription">Description</label>
                <input type="text" class="form-control" name="txtDescription" id="txtDescription" placeholder="Description" value="<?=$Description?>">
                </div>

            </div>

            <div class="row-fluid">

                <div class="col-xs-5 col-xs-offset-1">
                <label for="txtReleaseDate">Release Date</label>
                <input type="text" class="form-control" name="txtReleaseDate" id="txtReleaseDate" placeholder="Release Date" value="<?=$ReleaseDate?>">
            </div>

            <div class="col-xs-5">
                <label for="txtCertificate">Certificate</label>
                <select class="form-control" name="txtCertificate">
                    <option value="U">U</option>
                    <option value="PG">PG</option>
                    <option value="PG">12</option>
                    <option value="PG">15</option>
                    <option value="PG">18</option>
                </select>
            </div>

            </div>

            <div class="row-fluid">

                <div class="col-xs-5 col-xs-offset-1">
                    <label for="txtRuntime">Runtime</label>
                    <input type="text" class="form-control" name="txtRuntime" id="txtRuntime" placeholder="Runtime" value="<?=$Runtime?>">
                </div>

                <div class="col-xs-5">
                    <label for="txtGenre">Genre</label>
                    <select class="form-control" name="txtGenre">
                        <?=$Genres?>
                    </select>
                </div>

            </div>
            <div class="clearfix"></div>

            <div class="row-fluid" style="margin-top: 15px">

            <div class="col-xs-12" style="text-align: center">

                <button class="btn btn-default" type="submit"  name="btnSubmit" id="btnSubmit" value="Submit">Submit</button>
                <button class="btn btn-danger" type="reset" name="btnReset" id="btnReset">Reset</button>
                <a class="btn btn-success" href="index.php">Home</a>

        </div>

        </div>

        </div>


    </form>

</body>

