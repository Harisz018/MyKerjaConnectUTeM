<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $conn->real_escape_string($_POST['phone_no']);
    $update_sql = "UPDATE user SET phone_no='$phone' WHERE user_id='$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }
}

$user_sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$user_result = $conn->query($user_sql);
$user_data = $user_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body class="dashboard-body">

    <?php include 'headerDashboard.php'; ?>

    <div class="dashboard-container">
        <nav class="sidebar" id="sidebar">
            <a href="student-dashboard.php">Dashboard</a>
            <a href="student-browsejobs.php">Browse Jobs</a>
            <a href="student-applications.php">Applications</a>
            <a href="student-profile.php" style="background: #e0eafc; color: #0056b3;">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <h2>Manage Profile & Payment Attributes</h2>

            <form action="student-profile.php" method="POST" class="profile-grid">
                <div class="profile-section">
                    <h3>Academic & Personal Data</h3>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($user_data['name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Matric Number</label>
                        <input type="text" value="<?php echo htmlspecialchars($user_data['user_id']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                    </div>
                </div>

                <div class="profile-section">
                    <h3>Contact Details</h3>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="phone_no" placeholder="Enter phone number" value="<?php echo htmlspecialchars($user_data['phone_no']); ?>">
                    </div>
                    <button type="submit" class="submit-btn" style="width: auto; padding: 10px 20px;">Update Profile</button>
                </div>
            </form>
        </main>
    </div>
    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
    </footer>
</body>

</html>