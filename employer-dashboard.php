<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['employer_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

$employer_id = $_SESSION['employer_id'];
$employer_name = $_SESSION['name'];

$jobs_sql = "SELECT COUNT(*) as total_jobs FROM job WHERE employer_id = '$employer_id'";
$jobs_res = $conn->query($jobs_sql);
$total_jobs = $jobs_res->fetch_assoc()['total_jobs'];

$pending_sql = "SELECT COUNT(a.application_id) as pending_apps 
                FROM application a 
                JOIN job j ON a.job_id = j.job_id 
                WHERE j.employer_id = '$employer_id' AND a.status = 'Pending'";
$pending_res = $conn->query($pending_sql);
$pending_apps = $pending_res->fetch_assoc()['pending_apps'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard | MyKerjaConnectUTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div id="welcomeMessage" style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($employer_name); ?></div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="employer-dashboard.php" class="active" style="background: #e0eafc; color: #0056b3;">Dashboard</a>
            <a href="employer-manage-vacancies.php">Manage Vacancies</a>
            <a href="employer-review-apps.php">Review Apps</a>
            <a href="employer-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </aside>

        <main class="dashboard-content">
            <h2>Employer Vacancy Summary Monitor</h2>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3 style="font-size: 1.1rem; color: #555; font-weight: 600;">Total Posted Jobs</h3>
                    <h1 style="margin-top: 15px; font-size: 2rem; color: #333;">( <?php echo $total_jobs; ?> )</h1>
                </div>
                <div class="stat-card">
                    <h3 style="font-size: 1.1rem; color: #555; font-weight: 600;">Pending Apps</h3>
                    <h1 style="margin-top: 15px; font-size: 2rem; color: #333;">( <?php echo $pending_apps; ?> )</h1>
                </div>
                <div class="stat-card">
                    <h3 style="font-size: 1.1rem; color: #555; font-weight: 600;">Total Distributed Payout</h3>
                    <h1 style="margin-top: 15px; font-size: 2rem; color: #333;">( RM 0.00 )</h1>
                </div>
            </div>

            <h3 style="margin-top: 30px; font-weight: 600;">Department Actions & Broadcast Alerts</h3>
            <div class="updates-box">
                <p style="color: #333; font-weight: 600;">Logs: Dashboard is up to date.</p>
            </div>
        </main>
    </div>
</body>
</html>