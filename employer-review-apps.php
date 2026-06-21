<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['employer_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}
$employer_id = $_SESSION['employer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['app_id'])) {
    $app_id = $conn->real_escape_string($_POST['app_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $update_sql = "UPDATE application SET status='$status' WHERE application_id='$app_id'";
    $conn->query($update_sql);

    if ($status == 'Approved') {
        $get_job = $conn->query("SELECT job_id FROM application WHERE application_id='$app_id'");
        if($get_job->num_rows > 0) {
            $job_row = $get_job->fetch_assoc();
            $approved_job_id = $job_row['job_id'];
            
            $conn->query("UPDATE job SET status='Closed' WHERE job_id='$approved_job_id'");
            $conn->query("UPDATE application SET status='Rejected' WHERE job_id='$approved_job_id' AND application_id != '$app_id' AND status='Pending'");
        }
    }

    header("Location: employer-review-apps.php");
    exit();
}

$sql = "SELECT a.application_id, a.apply_date, a.status, u.name as student_name, j.title as job_title 
        FROM application a 
        JOIN user u ON a.user_id = u.user_id 
        JOIN job j ON a.job_id = j.job_id 
        WHERE j.employer_id = '$employer_id'
        ORDER BY a.apply_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Applications | MyKerjaConnectUTeM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div id="welcomeMessage" style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="employer-dashboard.php">Dashboard</a>
            <a href="employer-manage-vacancies.php">Manage Vacancies</a>
            <a href="employer-review-apps.php" class="active" style="background: #e0eafc; color: #0056b3;">Review Apps</a>
            <a href="employer-profile.php">Profile</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <main class="dashboard-content">
            <h2>Review Student Application</h2>
            <div class="updates-box" style="margin-top: 20px; padding: 20px;">
                <table class="application-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job Position</th>
                            <th>Applied date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apply_date']) . "</td>";
                                echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
                                echo "<td>";
                                if ($row['status'] == 'Pending') {
                                    echo "<form action='employer-review-apps.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='app_id' value='".$row['application_id']."'>
                                            <input type='hidden' name='status' value='Approved'>
                                            <button type='submit' style='padding: 6px 12px; font-size: 0.85rem; background-color: #28a745; color: white; border: none; border-radius: 4px; margin-right: 5px; cursor: pointer;'>Approve</button>
                                          </form>";
                                    echo "<form action='employer-review-apps.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='app_id' value='".$row['application_id']."'>
                                            <input type='hidden' name='status' value='Rejected'>
                                            <button type='submit' style='padding: 6px 12px; font-size: 0.85rem; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;'>Reject</button>
                                          </form>";
                                } else {
                                    echo "<span style='color: #666;'>Action Taken</span>";
                                }
                                echo "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No applications received yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>