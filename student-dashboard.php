<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$total_sql = "SELECT COUNT(*) as total FROM application WHERE user_id = '$user_id'";
$total_result = $conn->query($total_sql);
$total_apps = $total_result->fetch_assoc()['total'];

$approved_sql = "SELECT COUNT(*) as approved FROM application WHERE user_id = '$user_id' AND status = 'Approved'";
$approved_result = $conn->query($approved_sql);
$approved_apps = $approved_result->fetch_assoc()['approved'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div id="welcomeMessage">Welcome, <?php echo htmlspecialchars($name); ?></div>
    </header>

    <div class="dashboard-container">
        <nav class="sidebar" id="sidebar">
            <a href="student-dashboard.php" style="background: #e0eafc; color: #0056b3;">Dashboard</a>
            <a href="student-browsejobs.php">Browse Jobs</a>
            <a href="student-applications.php">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <section class="performance-summary">
                <h2>Student Performance Summary Monitor</h2>
                <div class="stats-grid">
                    <div class="stat-card">Total Applied<br><strong>( <?php echo $total_apps; ?> )</strong></div>
                    <div class="stat-card">Approved<br><strong>( <?php echo $approved_apps; ?> )</strong></div>
                    <div class="stat-card">Total Earnings<br><strong>( RM 0.00 )</strong></div>
                </div>
            </section>

            <section class="alerts-section">
                <h3>System Broadcast Alerts & Updates</h3>
                <div class="updates-box">
                    <p>Updates: Welcome to the new MyKerjaConnect system!</p>
                </div>
            </section>
        </main>
    </div>
    
    <footer class="dashboard-footer">
        <a href="#">Contact Us</a>
    </footer>
</body>
</html>