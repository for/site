<?php
require '../assets/db2.php';
session_start();

$id = Crypt::Decrypt($_GET['id']);

$link = Db::open();

$stmt = $link -> prepare("SELECT FileName, Movies.MovieID, Runtime, Certificate, Description, Genre, Title, ReleaseDate,
                        (SELECT TalentName FROM MovieTalent LEFT JOIN Talent ON Talent.TalentID = MovieTalent.TalentID WHERE MovieTalent.RoleID = 1 AND MovieTalent.MovieID = Movies.MovieID) AS Director,
                        (SELECT TalentName FROM MovieTalent LEFT JOIN Talent ON Talent.TalentID = MovieTalent.TalentID WHERE MovieTalent.RoleID = 2 AND MovieTalent.MovieID = Movies.MovieID) AS Producer,
                        (SELECT TalentName FROM MovieTalent LEFT JOIN Talent ON Talent.TalentID = MovieTalent.TalentID WHERE MovieTalent.RoleID = 3 AND MovieTalent.MovieID = Movies.MovieID) AS Screenwriter
                        FROM Movies LEFT JOIN Genres ON Genres.GenreID = Movies.GenreID LEFT JOIN ArtWork ON ArtWork.MovieID = Movies.MovieID AND ArtWork.CoverArt = 1 WHERE Movies.MovieID = ?");

$stmt -> bind_param('i', $id);

$stmt -> bind_result($FileName, $MovieID, $Runtime, $Certificate, $Description, $Genre, $Title, $ReleaseDate, $director, $Producer, $Screenwriter);

$stmt -> execute();

$stmt -> fetch();

$titles = "";

    $titles .= "<tr>";
    $titles .= "<td>" . $Title . "</td>";
    $titles .= "<td>" . $ReleaseDate . "</td>";
    $titles .= "<td>" . $Runtime . "</td>";
    $titles .= "<td>" . $Genre . "</td>";
    $titles .= "<td>" . $Certificate . "</td>";
    $titles .= "<td>" . $Description . "</td>";
    $titles .= "</tr>";

    $titles .= "<tr>";
    $titles .= "<td>" . $Title . "</td>";
    $titles .= "<td>" . $ReleaseDate . "</td>";
    $titles .= "<td>" . $Runtime . "</td>";
    $titles .= "<td>" . $Genre . "</td>";
    $titles .= "<td>" . $Certificate . "</td>";
    $titles .= "<td>" . $Description . "</td>";
    $titles .= "<td>" . $director . "</td>";
    $titles .= "</tr>";

$stmt -> close();

$stmt = $link -> prepare("SELECT Characters, TalentName FROM MovieTalent LEFT JOIN Talent ON Talent.TalentID = MovieTalent.TalentID WHERE MovieID = ? AND RoleID = 4");

$stmt -> bind_param('i', $id);

$stmt -> bind_result($Characters, $TalentName);

$stmt -> execute();

$talents = "";
while($stmt -> fetch()){
    $talents .= "<tr>";
    $talents .= "<td>" . $Characters . "</td>";
    $talents .= "<td>" . $TalentName . "</td>";
    $talents .= "</tr>";
};

$stmt -> close();

$stmt = $link -> prepare("SELECT TalentName FROM MovieTalent LEFT JOIN Talent ON Talent.TalentID = MovieTalent.TalentID LEFT JOIN Roles ON Roles.RoleID = MovieTalent.RoleID LEFT JOIN Movies ON Movies.MovieID = MovieTalent.MovieID WHERE MovieTalent.RoleID = ?");

$stmt -> bind_param('i', $id);

$stmt -> bind_result($TalentName);

//Check cover art in the db later for dups!!
$CoverArt = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOK = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    //Image check:
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOK = 1;
        } else {
            echo "File is not an image!";
            $uploadOK = 0;
        }
    }

    //does file exist?
    if (file_exists($target_file)) {
        echo "Sorry, file already exists";
        $uploadOK = 0;
    }

    //file size
    if ($_FILES["fileToUpload"]["size"] > 4718592) {
        echo "Sorry, file is to large";
        $uploadOK = 0;
    }

    //Only allow certain formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only jpeg, jpeg, png and gif are allowed";
    }

    //is $uploadOK set to 0 by error
    if ($uploadOK == 0) {
        echo "Sorry, your file was not uploaded";
        //Is everything ok?, if so try and upload the file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ".basename($_FILES["fileToUpload"]["name"]). " has been uploaded!";

            $stmt = $link -> prepare("INSERT INTO ArtWork(MovieID, CoverArt, FileName) VALUES (?,?,?)");

            $stmt -> bind_param("iis", $MovieID, $CoverArt, basename($_FILES["fileToUpload"]["name"]));

            $stmt -> execute();


        } else {
            echo "Sorry there was an error!";
        }
    }
}

$link -> close();

?>

<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">
    <style>
        .btext{
            color:cadetblue;
        }
    </style>
</head>

<body>




<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-md-offset-2"><br />
            <img src="uploads/<?= $FileName ?>" style="height: 317px; width: 214px;">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fileToUpload"><h6>Upload Cover Photo:</h6></label>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <br />
                    <input type="submit" value="Upload Image" name="submit"><br /><br />
                    <a class="btn btn-success" href="index.php">Home</a>
                    <a class="btn btn-danger" href="edit.php?id=<?=Crypt::encrypt($MovieID)?>">Edit</a>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <h1><?=$Title?></h1><br />
            <div class="btext">Description</div>
            <?=$Description?><br /><br />
            <div class="btext">Director</div>
            <?=$director?><br /><br />
            <div class="btext">Producer</div>
            <?=$Producer?><br /><br />
            <div class="btext">Screen Writer</div>
            <?=$Screenwriter?>
        </div>
    </div>
</div>

</body>
