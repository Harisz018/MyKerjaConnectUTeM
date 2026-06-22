<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['employer_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}
$employer_id = $_SESSION['employer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_job') {
    $title = $conn->real_escape_string($_POST['title']);
    $salary = $conn->real_escape_string($_POST['salary']);
    $location = $conn->real_escape_string($_POST['location']);
    $desc = $conn->real_escape_string($_POST['description']);
    $job_id = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
    
    $insert_sql = "INSERT INTO job (job_id, title, description, salary, location, status, employer_id) 
                   VALUES ('$job_id', '$title', '$desc', '$salary', '$location', 'Active', '$employer_id')";
    $conn->query($insert_sql);
    header("Location: employer-manage-vacancies.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $del_id = $conn->real_escape_string($_GET['delete_id']);
    $conn->query("DELETE FROM application WHERE job_id='$del_id'");
    $conn->query("DELETE FROM job WHERE job_id='$del_id' AND employer_id='$employer_id'");
    header("Location: employer-manage-vacancies.php");
    exit();
}

$jobs_sql = "SELECT j.*, a.status AS app_status, a.payment_status, wp.file_name AS proof_file 
             FROM job j 
             LEFT JOIN application a ON j.job_id = a.job_id AND a.status = 'Approved'
             LEFT JOIN work_proof wp ON a.application_id = wp.application_id
             WHERE j.employer_id = '$employer_id' 
             ORDER BY j.title ASC";
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vacancies | MyKerjaConnectUTeM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<body class="dashboard-body">
    <?php include 'headerDashboard.php'; ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="employer-dashboard.php">Dashboard</a>
            <a href="employer-manage-vacancies.php" class="active" style="background: #e0eafc; color: #0056b3;">Manage Vacancies</a>
            <a href="employer-review-apps.php">Review Apps</a>
            <a href="employer-profile.php">Profile</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <main class="dashboard-content">
            <h2>Add New Vacancy</h2>
            <div class="updates-box" style="margin-top: 10px; padding: 20px;">
                <form action="employer-manage-vacancies.php" method="POST" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <input type="hidden" name="action" value="add_job">
                    <input type="text" name="title" placeholder="Job Title (e.g. Lab Assistant)" required style="padding:10px;">
                    <input type="number" step="0.01" name="salary" placeholder="Rate RM" required style="padding:10px;">
                    
                    <select name="location" required style="padding:10px;">
                        <option value="" disabled selected>Select Location/Faculty</option>
                        <option value="FTMK">FTMK</option>
                        <option value="FTKEK">FTKEK</option>
                        <option value="FTKIP">FTKIP</option>
                        <option value="FTKM">FTKM</option>
                        <option value="FPTT">FPTT</option>
                        <option value="FTKE">FTKE</option>
                        <option value="FAIX">FAIX</option>
                    </select>

                    <input type="text" name="description" placeholder="Short Description" required style="padding:10px; flex-grow: 1;">
                    <button type="submit" class="apply-btn" style="padding: 10px 20px;">Post Job</button>
                </form>
            </div>

            <h2 style="margin-top: 30px;">Management Department Vacancies Control Log</h2>
            <div class="updates-box" style="margin-top: 20px; padding: 20px;">
                <table class="application-table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Rate (RM)</th>
                            <th>Work Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($jobs_result->num_rows > 0) {
                            while($row = $jobs_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                echo "<td>RM " . htmlspecialchars($row['salary']) . "</td>";
                                
                                echo "<td>";
                                if ($row['app_status'] == 'Approved') {
                                    if ($row['payment_status'] == 'Paid') {
                                        echo "<span style='color: #28a745; font-weight: 600;'>✔ Completed & Paid</span>";
                                    } elseif (!empty($row['proof_file'])) {
                                        echo "<span style='color: #17a2b8; font-weight: 600;'>📁 Work Submitted</span>";
                                    } else {
                                        echo "<span style='color: #ff9800; font-weight: 600;'>⏳ In Progress</span>";
                                    }
                                } else {
                                    echo "<span style='color: #007bff; font-weight: 600;'>🟢 Open (Hiring)</span>";
                                }
                                echo "</td>";

                                echo "<td>
                                        <a href='employer-manage-vacancies.php?delete_id=" . $row['job_id'] . "' 
                                           style='padding: 6px 12px; font-size: 0.85rem; background-color: #dc3545; color: white; text-decoration: none; border-radius:4px;' 
                                           onclick='return confirm(\"Are you sure you want to delete this job?\");'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>You have not posted any jobs yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <footer>
            <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
        </footer>
</body>
</html>