<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$link = new mysqli($servername, $username, $password);

// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
} 
echo "asdasd";
?>