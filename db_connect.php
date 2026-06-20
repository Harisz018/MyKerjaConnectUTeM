<?php
$host = "127.0.0.1"; // As seen in your phpMyAdmin screenshot
$user = "root";      // Default XAMPP/Apache username
$pass = "";          // Default XAMPP/Apache password
$db   = "mykerjaconnectdb";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}
?>