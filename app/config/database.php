<?php
$servername = "127.0.0.1"; 
$username = "root";
$password = ""; 
$dbname = "codl";      
$conn = mysqli_connect($servername, $username, $password, $dbname, 3306);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>