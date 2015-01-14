<?php
require '../functions/db2.php';
session_start();

$error = "";
$btnsuc = "btn-default";
$btntxt = "Submit";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['btnSubmit'])){

        if ($_POST['txtTitle'] == ""){
            $error .= "You need to have a title! <br />";
        }
        if ($_POST['txtDescription'] == ""){
            $error .= "You need to have a description! <br />";
        }
        if ($_POST['txtReleaseDate'] == ""){
            $error .= "You need to have a release date! <br />";
        }
        if ($_POST['txtRuntime'] == ""){
            $error .= "You need to have a runtime! <br />";
        }

            if($error == ""){

            $link = Db::open();

                $info = array(
                    $_POST['txtTitle'],
                    $_POST['txtDescription'],
                    $_POST['txtReleaseDate'],
                    $_POST['txtRuntime'],
                    $_POST['txtCertificate'],
                    $_POST['txtGenre']
                );

                if($info[2] == ''){
                    $info[2] = null;
                }

                if($info[3] == ''){
                    $info[3] = null;
                }

                if($info[5] == ''){
                    $info[5] = null;
                }

                $stmt = $link -> prepare("INSERT INTO Movies (Title, Description, ReleaseDate, Runtime, Certificate, GenreID) VALUES (?,?,?,?,?,?)");

                $stmt -> bind_param('sssssi', $info[0], $info[1], $info[2], $info[3], $info[4], $info[5]);

                if ($stmt -> execute()){

                    $btnsuc = "btn-success";
                    $btntxt = "Saved!";

                }
                else {
                    $btnsuc = "btn-danger";
                    $btntxt = "Failed-" . $stmt -> error;
                }

                //echo $stmt -> error;

                $stmt -> close();
            }
        }
    }

$Genres = "";

$link = Db::open();

$stmt2 = $link -> prepare("SELECT GenreID, Genre FROM Genres");

$stmt2 -> bind_result($GenreID, $Genre);

$stmt2 -> execute();

while($stmt2 -> fetch()){
    $Genres .= "<option value='$GenreID'>$Genre</option>";
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
                <input type="text" class="form-control" name="txtTitle" id="txtTitle" placeholder="Title">
                </div>

                <div class="col-xs-5">
                <label for="txtDescription">Description</label>
                <input type="text" class="form-control" name="txtDescription" id="txtDescription" placeholder="Description">
                </div>

            </div>

            <div class="row-fluid">

                <div class="col-xs-5 col-xs-offset-1">
                <label for="txtReleaseDate">Release Date</label>
                <input type="text" class="form-control" name="txtReleaseDate" id="txtReleaseDate" placeholder="Release Date">
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
                    <input type="text" class="form-control" name="txtRuntime" id="txtRuntime" placeholder="Runtime">
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

                    <button class="btn <?echo $btnsuc;?>" type="submit"  name="btnSubmit" id="btnSubmit" value="Submit"><?echo $btntxt;?></button>
                    <a class="btn btn-success" href="index.php">Home</a>

                <?php
                echo "<div style='color: red;'>" . $error . "</div>";
                ?>

            </div>

            </div>

        </div>

    </form>

</body>


