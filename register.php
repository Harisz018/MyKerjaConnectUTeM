<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['userType'];
    $name = $conn->real_escape_string($_POST['regName']);
    $id = $conn->real_escape_string($_POST['regMatric']); 
    $email = $conn->real_escape_string($_POST['regEmail']);
    $password = $conn->real_escape_string($_POST['regPassword']);

    if ($role == 'student') {
        
        $sql = "INSERT INTO user (user_id, name, email, password, role) VALUES ('$id', '$name', '$email', '$password', 'student')";
    } else {
        
        $sql = "INSERT INTO employer (employer_id, company_name, email, password) VALUES ('$id', '$name', '$email', '$password')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">MyKerjaConnectUTeM</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main class="form-container">
        <div class="registration-card">
            <h2>Create Your Account</h2>
            <form action="register.php" method="POST">
                <div class="radio-group">
                    <label><input type="radio" name="userType" value="student" checked> Student</label>
                    <label><input type="radio" name="userType" value="employer"> Employer</label>
                </div>
                <input type="text" name="regName" placeholder="Full Name / Company Name" required>
                <input type="text" name="regMatric" placeholder="Matric Number / Employer ID" required>
                <input type="email" name="regEmail" placeholder="UTeM Email Address" required>
                <input type="password" name="regPassword" placeholder="Password" required>
                
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="#">Contact Us</a></p>
    </footer>
</body>
</html>