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
            echo "<script>alert('Application submitted successfully!'); window.location.href='student-browsejobs.php';</script>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_job_id'])) {
    $cancel_id = $conn->real_escape_string($_POST['cancel_job_id']);
    $conn->query("DELETE FROM application WHERE job_id='$cancel_id' AND user_id='$user_id'");
    echo "<script>alert('Application cancelled.'); window.location.href='student-browsejobs.php';</script>";
}

$applied_jobs = [];
$app_chk = $conn->query("SELECT job_id FROM application WHERE user_id='$user_id'");
while($ac = $app_chk->fetch_assoc()){
    $applied_jobs[] = $ac['job_id'];
}

$search_keyword = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$faculty_filter = isset($_GET['faculty']) ? $conn->real_escape_string($_GET['faculty']) : '';

$jobs_sql = "SELECT * FROM job WHERE status = 'Active'";

if (!empty($search_keyword)) {
    $jobs_sql .= " AND (title LIKE '%$search_keyword%' OR description LIKE '%$search_keyword%')";
}
if (!empty($faculty_filter)) {
    $jobs_sql .= " AND location LIKE '%$faculty_filter%'";
}

$jobs_sql .= " ORDER BY job_id DESC";
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Jobs | MyKerjaConnect UTeM</title>
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
            <a href="student-browsejobs.php" style="background: #e0eafc; color: #0056b3;" class="active">Browse Jobs</a>
            <a href="student-applications.php">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <form action="student-browsejobs.php" method="GET" class="filter-section" style="grid-template-columns: 2fr 1fr auto;">
                <input type="text" name="search" placeholder="Search Jobs..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                
                <select name="faculty">
                    <option value="" selected>All Locations</option>
                    <option value="FTMK" <?php if($faculty_filter == 'FTMK') echo 'selected'; ?>>FTMK</option>
                    <option value="FTKEK" <?php if($faculty_filter == 'FTKEK') echo 'selected'; ?>>FTKEK</option>
                    <option value="FTKIP" <?php if($faculty_filter == 'FTKIP') echo 'selected'; ?>>FTKIP</option>
                    <option value="FTKM" <?php if($faculty_filter == 'FTKM') echo 'selected'; ?>>FTKM</option>
                    <option value="FPTT" <?php if($faculty_filter == 'FPTT') echo 'selected'; ?>>FPTT</option>
                    <option value="FTKE" <?php if($faculty_filter == 'FTKE') echo 'selected'; ?>>FTKE</option>
                    <option value="FAIX" <?php if($faculty_filter == 'FAIX') echo 'selected'; ?>>FAIX</option>
                </select>
                
                <button type="submit" style="padding: 12px 25px;">Search</button>
            </form>

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
                        
                        if (in_array($row['job_id'], $applied_jobs)) {
                            echo '<form action="student-browsejobs.php" method="POST" style="margin:0;">';
                            echo '<input type="hidden" name="cancel_job_id" value="' . $row['job_id'] . '">';
                            echo '<button type="submit" class="apply-btn" style="background-color: #dc3545;" onclick="return confirm(\'Cancel this application?\');">Cancel</button>';
                            echo '</form>';
                        } else {
                            echo '<form action="student-browsejobs.php" method="POST" style="margin:0;">';
                            echo '<input type="hidden" name="apply_job_id" value="' . $row['job_id'] . '">';
                            echo '<button type="submit" class="apply-btn">Apply</button>';
                            echo '</form>';
                        }
                        
                        echo '</div>';
                    }
                } else {
                    echo "<p>No active jobs available matching your search.</p>";
                }
                ?>
            </section>
        </main>
    </div>
</body>
</html>