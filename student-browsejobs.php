<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_job_id'])) {
    $job_id = $conn->real_escape_string($_POST['apply_job_id']);
    $app_id = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11); 
    $apply_date = date('Y-m-d');

    $check_sql = "SELECT * FROM application WHERE user_id='$user_id' AND job_id='$job_id'";
    $check_res = $conn->query($check_sql);

    if ($check_res->num_rows == 0) {
        $insert_sql = "INSERT INTO application (application_id, apply_date, status, user_id, job_id) 
                       VALUES ('$app_id', '$apply_date', 'Pending', '$user_id', '$job_id')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>alert('Application submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error applying: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('You have already applied for this job.');</script>";
    }
}

$jobs_sql = "SELECT * FROM job ORDER BY job_id DESC";
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs | MyKerjaConnect UTeM</title>
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
            <a href="student-browsejobs.php" style="background: #e0eafc; color: #0056b3;">Browse Jobs</a>
            <a href="student-applications.php">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <section class="filter-section">
                <input type="text" placeholder="Search Jobs">
                <select name="faculty">
                    <option value="" disabled selected>Select Location</option>
                    <option value="ftmk">FTMK</option>
                    <option value="ftkek">FTKEK</option>
                </select>
            </section>

            <section class="job-list-container">
                <?php
                if ($jobs_result->num_rows > 0) {
                    while($row = $jobs_result->fetch_assoc()) {
                        echo '<div class="job-card">';
                        echo '<div class="info">';
                        echo '<strong>' . htmlspecialchars($row['title']) . ' (' . htmlspecialchars($row['location']) . ')</strong><br>';
                        echo 'Rate: RM' . htmlspecialchars($row['salary']) . '/H <br>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        echo '</div>';
                        echo '<form action="student-browsejobs.php" method="POST" style="margin:0;">';
                        echo '<input type="hidden" name="apply_job_id" value="' . $row['job_id'] . '">';
                        echo '<button type="submit" class="apply-btn">Apply</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No jobs available at the moment.</p>";
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>