<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$app_count_sql = "SELECT COUNT(*) as total FROM application WHERE user_id='$user_id'";
$total_applied = $conn->query($app_count_sql)->fetch_assoc()['total'];

$appr_count_sql = "SELECT COUNT(*) as total FROM application WHERE user_id='$user_id' AND status='Approved'";
$total_approved = $conn->query($appr_count_sql)->fetch_assoc()['total'];

$earn_sql = "SELECT SUM(j.salary) as total_earned 
             FROM application a 
             JOIN job j ON a.job_id = j.job_id 
             WHERE a.user_id = '$user_id' AND a.payment_status = 'Paid'";
$earn_res = $conn->query($earn_sql);
$total_earned = $earn_res->fetch_assoc()['total_earned'];
$total_earned = $total_earned ? $total_earned : 0.00;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | MyKerjaConnectUTeM</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div id="welcomeMessage" style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($name); ?></div>
    </header>

    <div class="dashboard-container">
        <nav class="sidebar" id="sidebar">
            <a href="student-dashboard.php" class="active" style="background: #e0eafc; color: #0056b3;">Dashboard</a>
            <a href="student-browsejobs.php">Browse Jobs</a>
            <a href="student-applications.php">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <h2>Student Performance Summary Monitor</h2>

            <div class="stats-grid">
                <div class="stat-card" style="padding: 20px;">
                    <h3 style="font-size: 1rem; color: #555;">Total Applied</h3>
                    <h1 style="margin-top: 10px; font-size: 1.8rem; color: #333;">( <?php echo $total_applied; ?> )</h1>
                </div>
                <div class="stat-card" style="padding: 20px;">
                    <h3 style="font-size: 1rem; color: #555;">Approved</h3>
                    <h1 style="margin-top: 10px; font-size: 1.8rem; color: #333;">( <?php echo $total_approved; ?> )</h1>
                </div>
                <div class="stat-card" style="padding: 20px;">
                    <h3 style="font-size: 1rem; color: #555;">Total Earnings</h3>
                    <h1 style="margin-top: 10px; font-size: 1.8rem; color: #333;">( RM <?php echo number_format((float)$total_earned, 2, '.', ''); ?> )</h1>
                </div>
            </div>

            <h2 style="margin-top: 30px;">System Broadcast Alerts & Updates</h2>
            <div class="updates-box" style="margin-top: 20px; padding: 20px;">
                <p style="color: #333;">Updates: Welcome to the new MyKerjaConnect system!</p>
            </div>
        </main>
    </div>
</body>

</html>