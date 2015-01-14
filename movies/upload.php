<?php
require '../assets/db2.php';
session_start();
?>

<?php
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
        } else {
            echo "Sorry there was an error!";
        }
    }
}

?>

<?php

    $link = Db::open();

    $stmt = $link -> prepare("INSERT INTO ArtWork()");

?>


<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fileToUpload">Select file to be uploaded:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <br />
            <input type="submit" value="Upload Image" name="submit">
        </div>
    </form>
</div>

</body>
</html>