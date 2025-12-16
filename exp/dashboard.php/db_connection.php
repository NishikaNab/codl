<?php

$servername = "localhost";  // Always localhost for XAMPP
$username = "root";         // Default XAMPP MySQL username
$password = "abc123";             // Default XAMPP MySQL password is empty
$dbname = "workshop2";    // Your database name in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // Optional for testing
?>
