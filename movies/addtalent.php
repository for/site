<?php
require '../assets/db2.php';
session_start();

$error = "";
$btnsuc = "btn-default";
$btntxt = "Submit";

$Roles = "";
$Movies = "";
$Talents = "";

$link = Db::open();

$stmt = $link -> prepare("SELECT RoleID, Role FROM Roles");

$stmt -> bind_result($RoleID, $Role);

$stmt -> execute();

while($stmt -> fetch()){
    $Roles .= "<option value='$RoleID'>$Role</option>";
}

$stmt-> close();

$stmt = $link -> prepare("SELECT MovieID, Title FROM Movies");

$stmt -> bind_result($MovieID, $Movie);

$stmt -> execute();

while($stmt -> fetch()){
    $Movies .= "<option value='$MovieID'>$Movie</option>";
}

$stmt -> close();

$stmt = $link -> prepare("SELECT TalentID, TalentName FROM Talent");

$stmt -> bind_result($TalentID, $TalentName);

$stmt -> execute();

while($stmt -> fetch()){
    $Talents .= "<option value='$TalentID'>$TalentName</option>";
}

$stmt -> close();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['btnSubmit'])){

        $info = array(
            $_POST['txtMovies'],
            $_POST['txtTalent'],
            $_POST['txtRoles'],
            $_POST['txtCharacters']
        );


        if($error == ""){

            $link = Db::open();

            if (strlen($_POST['txtNewTalent']) > 0){

                $stmt = $link -> prepare("INSERT INTO Talent(TalentName) VALUES(?)");

                $stmt -> bind_param('s', $_POST['txtNewTalent']);

                $stmt -> execute();

                $info[1] = $stmt -> insert_id;

                $stmt -> close();
            }

            $stmt = $link -> prepare("INSERT INTO MovieTalent(MovieID, TalentID, RoleID, Characters) VALUES (?,?,?,?)");

            $stmt -> bind_param('iiis', $info[0], $info[1], $info[2], $info[3]);

            if ($stmt -> execute()){

                $btnsuc = "btn-success";
                $btntxt = "Saved!";

            }
            else {
                $btnsuc = "btn-danger";
                $btntxt = "Failed-" . $stmt -> error;
            }

            $stmt -> close();

        }
    }

}

?>

<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">

    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("select").change(function(){
                $( "select option:selected").each(function(){
                    if($(this).attr("value")=="-1"){
                        $("#txtNewTalent").show();
                    } else {
                        $("#txtNewTalent").hide();
                    }
                });
            }).change();
        });
    </script>

    <style>
        label{
            margin-top: 0.5em;
        }
    </style>
</head>

<body>

<form method="POST">

    <div class="container-fluid">

        <div class="row-fluid">

            <div class="col-xs-5 col-xs-offset-1">
                <label for="txtCharacters">Character</label>
                <input type="text" class="form-control" name="txtCharacters" id="txtCharacters" placeholder="Characters">
            </div>

            <div class="col-xs-5">
                <label for="txtMovies">Movie</label>
                <select class="form-control" name="txtMovies">
                    <?=$Movies?>
                </select>
            </div>

        </div>

        <div class="row-fluid">

            <div class="col-xs-5 col-xs-offset-1">
                <label for="txtRoles">Role</label>
                <select class="form-control" name="txtRoles">
                    <?=$Roles?>
                </select>
            </div>

            <div class="col-xs-5">
                <label for="txtTalent">Talent</label>
                <select class="form-control" name="txtTalent">
                    <?=$Talents?>
                    <option value="-1">New Talent</option>
                </select>
                <input style="display: none; margin-top: 1em;" type="text" class="form-control" name="txtNewTalent" id="txtNewTalent" placeholder="New Talent">
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