<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['userType'];
    $name = $conn->real_escape_string($_POST['regName']);
    $email = $conn->real_escape_string($_POST['regEmail']);
    $password = password_hash($_POST['regPassword'], PASSWORD_DEFAULT);

    if ($role == 'student') {
        $id = $conn->real_escape_string($_POST['regMatric']);
        $sql = "INSERT INTO user (user_id, name, email, password, role) VALUES ('$id', '$name', '$email', '$password', 'student')";
    } else {
        $id = $conn->real_escape_string($_POST['regEmployerID']);
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
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleFormFields() {
            const role = document.querySelector('input[name="userType"]:checked').value;
            const nameInput = document.getElementById('regName');
            const idGroupStudent = document.getElementById('studentIdGroup');
            const idGroupEmployer = document.getElementById('employerIdGroup');

            if (role === 'student') {
                nameInput.placeholder = "Full Name";
                idGroupStudent.style.display = "block";
                document.getElementById('regMatric').required = true;

                idGroupEmployer.style.display = "none";
                document.getElementById('regEmployerID').required = false;
            } else {
                nameInput.placeholder = "Company Name";
                idGroupEmployer.style.display = "block";
                document.getElementById('regEmployerID').required = true;

                idGroupStudent.style.display = "none";
                document.getElementById('regMatric').required = false;
            }
        }

        function validateForm() {
            const email = document.getElementById("regEmail").value;
            const password = document.getElementById("regPassword").value;

            if (password.length < 6) {
                alert("Security Warning: Password must be at least 6 characters long.");
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
</head>

<body onload="toggleFormFields()">

    <?php include 'headerPublic.php'; ?>

    <main class="form-container">
        <div class="registration-card">
            <h2>Create Your Account</h2>

            <form action="register.php" method="POST" onsubmit="return validateForm()">
                <div class="radio-group">
                    <label><input type="radio" name="userType" value="student" checked onchange="toggleFormFields()"> Student</label>
                    <label><input type="radio" name="userType" value="employer" onchange="toggleFormFields()"> Employer</label>
                </div>

                <input type="text" name="regName" id="regName" placeholder="Full Name" required>

                <div id="studentIdGroup">
                    <input type="text" name="regMatric" id="regMatric" placeholder="Matric Number">
                </div>

                <div id="employerIdGroup" style="display:none;">
                    <input type="text" name="regEmployerID" id="regEmployerID" placeholder="Employer ID">
                </div>

                <input type="email" name="regEmail" id="regEmail" placeholder="UTeM Email Address" required>
                <input type="password" name="regPassword" id="regPassword" placeholder="Password" required>

                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
    </footer>

</body>

</html>