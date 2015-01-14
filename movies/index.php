<?php
//require '../functions/db2.php';
require '../assets/config.php';
session_start();
?>

<?php

$link = Db::open();

$stmt = $link -> prepare("SELECT MovieID, Genre, Title, ReleaseDate FROM Movies LEFT JOIN Genres ON Genres.GenreID = Movies.GenreID");

$stmt -> bind_result($MovieID, $Genre, $Title, $ReleaseDate);

$stmt -> execute();

$titles = "";
while ($stmt -> fetch()){
    $titles .= "<tr onclick=\"document.location.href='movieinfo.php?id=" . crypt::encrypt($MovieID) . "'\";>";
    $titles .= "<td>" . $Title . "</td>";
    $titles .= "<td>" . $ReleaseDate . "</td>";
    $titles .= "<td>" . $Genre . "</td>";
    $titles .= "</tr>";

}

?>

<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">

        <table class="table table-bordered">

            <tr>
                <th>Movie</th>
                <th>Release Date</th>
                <th>Genre</th>
            </tr>
            <?= $titles ?>
        </table>

        <a class="btn btn-warning" href="add.php">Add</a>
        <a class="btn btn-danger" href="addtalent.php">Add talent</a>

    </div>

</body>


