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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_app_id'])) {
    $pay_app_id = $conn->real_escape_string($_POST['pay_app_id']);
    $conn->query("UPDATE application SET payment_status='Paid' WHERE application_id='$pay_app_id'");
    header("Location: employer-review-apps.php");
    exit();
}

$sql = "SELECT a.application_id, a.apply_date, a.status, a.payment_status, wp.file_name as proof_file, u.name as student_name, j.title as job_title, j.salary 
        FROM application a 
        JOIN user u ON a.user_id = u.user_id 
        JOIN job j ON a.job_id = j.job_id 
        LEFT JOIN work_proof wp ON a.application_id = wp.application_id
        WHERE j.employer_id = '$employer_id'
        ORDER BY a.apply_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                            <th>Status</th>
                            <th>Proof of Work</th>
                            <th>Actions & Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['job_title']) . " (RM" . htmlspecialchars($row['salary']) . ")</td>";
                                echo "<td><strong>" . htmlspecialchars($row['status']) . "</strong></td>";
                                
                                echo "<td>";
                                if (!empty($row['proof_file'])) {
                                    echo "<a href='uploads/" . htmlspecialchars($row['proof_file']) . "' target='_blank' style='background-color:#17a2b8; color:white; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:0.85rem;'>View Report</a>";
                                } else {
                                    echo "<span style='color:#999; font-size:0.85rem;'>Not submitted</span>";
                                }
                                echo "</td>";

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
                                } elseif ($row['status'] == 'Approved') {
                                    if ($row['payment_status'] == 'Paid') {
                                        echo "<span style='color: #28a745; font-weight: bold; font-size: 0.85rem;'>✔ Payment Completed</span>";
                                    } else {
                                        echo "<form action='employer-review-apps.php' method='POST' style='display:inline;'>
                                                <input type='hidden' name='pay_app_id' value='".$row['application_id']."'>
                                                <button type='submit' style='padding: 6px 12px; font-size: 0.85rem; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;' onclick='return confirm(\"Confirm payment of RM " . $row['salary'] . " to student?\");'>Make Payment (RM " . $row['salary'] . ")</button>
                                              </form>";
                                    }
                                } else {
                                    echo "<span style='color: #666; font-size: 0.85rem;'>Rejected</span>";
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