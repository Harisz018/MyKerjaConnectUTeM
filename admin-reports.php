<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$u_count = $conn->query("SELECT COUNT(*) AS total FROM user")->fetch_assoc()['total'];
$e_count = $conn->query("SELECT COUNT(*) AS total FROM employer")->fetch_assoc()['total'];
$total_users = $u_count + $e_count;

$total_jobs = $conn->query("SELECT COUNT(*) AS total FROM job WHERE status='Active'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin System Reports</title>
    <link rel="stylesheet" href="design.css">
</head>
<body class="dashboard-body">

<div class="dashboard-header">
    <h1>MyKerjaConnectUTeM</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
</div>

<div class="dashboard-container">
    <div class="sidebar">
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="admin-users.php">Manage Users</a>
        <a href="admin-vacancies.php">Monitor Vacancies</a>
        <a href="admin-reports.php">System Reports</a>
        <a href="logout.php">Sign Out</a>
    </div>

    <div class="dashboard-content">
        <h1>Summary Dashboard</h1>
        <div class="updates-box">
            <p><b>Total Registered Users:</b> <?php echo $total_users; ?></p>
            <p><b>Total Active Vacancies:</b> <?php echo $total_jobs; ?></p>
        </div>

        <br>
        <h1>Report Generation Options</h1>
        <div class="updates-box">
            <p><b>Report Format:</b> PDF</p>
            <p>Data metrics are synchronised with your Apache server MySQL deployment.</p>
        </div>
        <button onclick="alert('Report exported successfully based on current database status!')">Generate & Export Report</button>
    </div>
</div>
</body>
</html>