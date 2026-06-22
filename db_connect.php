<?php
$host = "127.0.0.1"; 
$user = "root";      
$pass = "";          
$db   = "mykerjaconnectdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}
?>