<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$app_sql = "SELECT a.apply_date, a.status, j.title, j.location 
            FROM application a 
            JOIN job j ON a.job_id = j.job_id 
            WHERE a.user_id = '$user_id' 
            ORDER BY a.apply_date DESC";
$app_result = $conn->query($app_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications | MyKerjaConnect UTeM</title>
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
            <a href="student-dashboard.php">Dashboard</a>
            <a href="student-browsejobs.php">Browse Jobs</a>
            <a href="student-applications.php" style="background: #e0eafc; color: #0056b3;">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <h2>Your submitted application logs:</h2>
            <br>
            <table class="application-table">
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($app_result->num_rows > 0) {
                        while($row = $app_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['apply_date']) . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>You have not applied for any jobs yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>