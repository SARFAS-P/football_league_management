<?php
$servername = "localhost";
$username = "root";
$password = "root"; 
$dbname = "dbproj";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if(!$conn)
{
    die("not connected".mysqli_connect_error());
}
;

?>
