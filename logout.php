<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "root"; // Your MySQL root password
$dbname = "dbproj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if(!$conn)
{
    die("not connected".mysqli_connect_error());
}





session_destroy();
header("Location: index.html");
exit();
?>
