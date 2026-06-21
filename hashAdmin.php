<?php
// Ensure the database connection is correct
require 'db_connect.php';

// =========================================================================
// ADMIN 1: PRIMARY ADMIN
// Edit the information below before running it in the browser
// =========================================================================


$admin_id       = "A001";                           
$admin_name     = "Ahmad Ikhwan";                   
$admin_email    = "D032410015@student.utem.edu.my"; 
$plain_password = "abc123";                

$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Check if Admin 1 already exists
$check_sql = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    $sql = "UPDATE admin SET name='$admin_name', email='$admin_email', password='$hashed_password' WHERE admin_id='$admin_id'";
    $action = "updated";
} else {
    $sql = "INSERT INTO admin (admin_id, name, email, password) VALUES ('$admin_id', '$admin_name', '$admin_email', '$hashed_password')";
    $action = "inserted";
}

// Execute SQL for Admin 1
if ($conn->query($sql) === TRUE) {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px 50px; padding: 20px; border: 1px solid #28a745; background-color: #d4edda; border-radius: 8px;'>";
    echo "<h2 style='color: #155724;'>✅ Success!</h2>";
    echo "<p>Details for Admin ID <b>$admin_id</b> have been successfully $action in the database.</p>";
    echo "<p>The password has been securely hashed.</p>";
    echo "</div>";
} else {
    echo "<p>Error: " . $conn->error . "</p>";
}





// =========================================================================
// ADMIN 2: SECONDARY ADMIN
// Uncomment this entire block (remove /* and */) to add a second admin
// =========================================================================


$admin2_id       = "A002";
$admin2_name     = "Daniel Imran";
$admin2_email    = "daniel@student.utem.edu.my";
$admin2_plain_pw = "abc123";

$hashed_password_2 = password_hash($admin2_plain_pw, PASSWORD_DEFAULT);

$check_sql_2 = "SELECT * FROM admin WHERE admin_id = '$admin2_id'";
$result_2 = $conn->query($check_sql_2);

if ($result_2->num_rows > 0) {
    $sql_2 = "UPDATE admin SET name='$admin2_name', email='$admin2_email', password='$hashed_password_2' WHERE admin_id='$admin2_id'";
    $action_2 = "updated";
} else {
    $sql_2 = "INSERT INTO admin (admin_id, name, email, password) VALUES ('$admin2_id', '$admin2_name', '$admin2_email', '$hashed_password_2')";
    $action_2 = "inserted";
}

if ($conn->query($sql_2) === TRUE) {
    echo "<div style='font-family: Arial, sans-serif; margin: 20px 50px; padding: 20px; border: 1px solid #28a745; background-color: #d4edda; border-radius: 8px;'>";
    echo "<h2 style='color: #155724;'>✅ Success (Admin 2)!</h2>";
    echo "<p>Details for Admin ID <b>$admin2_id</b> have been successfully $action_2 in the database.</p>";
    echo "<p>The password has been securely hashed.</p>";
    echo "</div>";
} else {
    echo "<p>Error: " . $conn->error . "</p>";
}


// =========================================================================

// Final Security Warning Broadcast
echo "<div style='font-family: Arial, sans-serif; margin: 20px 50px; padding: 20px; border: 1px solid #dc3545; background-color: #f8d7da; border-radius: 8px;'>";
echo "<p style='color: #dc3545; font-weight: bold;'>WARNING: Please remove this 'hashAdmin.php' file from the server (htdocs) immediately for security purposes.</p>";
echo "</div>";

$conn->close();
?>