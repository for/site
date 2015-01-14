<?php
$username = "root";
$password = "";
$hostname = "localhost"; 

//connection to the database
$db = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
echo "Connected to MySQL<br>";
?>


<?php
//select a database to work with
$selected = mysql_select_db("movies",$db) 
  or die("Could not select examples");
?>
<?php
$stmt = $db("SELECT MovieID, Title, ReleaseDate FROM Movies");

$stmt -> bind_result($MovieID, $Title, $ReleaseDate);

$stmt -> execute();

$titles = "";
while ($stmt -> fetch()){
    $titles .= "<tr onclick=\"document.location.href='movieinfo.php?id=" . crypt::encrypt($MovieID) . "'\";>";
    $titles .= "<td>" . $Title . "</td>";
    $titles .= "<td>" . $ReleaseDate . "</td>";
    $titles .= "</tr>";

}

?>