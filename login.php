<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['userRole'];
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    if ($role == 'student') {
        $sql = "SELECT * FROM user WHERE email='$email' AND role='student'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password_input, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = 'student';
                header("Location: student-dashboard.php");
                exit();
            }
        }
    } elseif ($role == 'employer') {
        $sql = "SELECT * FROM employer WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password_input, $row['password'])) {
                $_SESSION['employer_id'] = $row['employer_id'];
                $_SESSION['name'] = $row['company_name'];
                $_SESSION['role'] = 'employer';
                header("Location: employer-dashboard.php");
                exit();
            }
        }
    } elseif ($role == 'admin') {
        $sql = "SELECT * FROM admin WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password_input, $row['password'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = 'admin';
                header("Location: admin-dashboard.php");
                exit();
            }
        }
    }
    echo "<script>alert('Access Denied: Credentials do not match.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>

    <?php include 'headerPublic.php'; ?>

    <main class="form-container">
        <div class="registration-card">
            <h2>Login</h2>

            <form action="login.php" method="POST" onsubmit="return validateLogin()">
                <div class="radio-group">
                    <label><input type="radio" name="userRole" value="student" checked> Student</label>
                    <label><input type="radio" name="userRole" value="admin"> Admin</label>
                    <label><input type="radio" name="userRole" value="employer"> Employer</label>
                </div>

                <input type="email" name="email" id="loginEmail" placeholder="UTeM Email Address" required>
                <input type="password" name="password" id="loginPassword" placeholder="Password" required>

                <button type="submit" class="submit-btn">Sign In</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
    </footer>

    <script>
        function validateLogin() {
            const email = document.getElementById("loginEmail").value;
            const password = document.getElementById("loginPassword").value;


            if (password.length < 6) {
                alert("Invalid Login: Password must be at least 6 characters.");
                return false;
            }

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Invalid Format: Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>

</body>

</html>