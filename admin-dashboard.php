<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

if (isset($_GET['delete_user'])) {
    $user_id = $conn->real_escape_string($_GET['delete_user']);
    
    $check_student = $conn->query("SELECT * FROM user WHERE user_id='$user_id'");
    if ($check_student->num_rows > 0) {
        $conn->query("DELETE FROM application WHERE user_id = '$user_id'");
        $conn->query("DELETE FROM user WHERE user_id = '$user_id'");
    }

    $check_employer = $conn->query("SELECT * FROM employer WHERE employer_id='$user_id'");
    if ($check_employer->num_rows > 0) {
        $jobs = $conn->query("SELECT job_id FROM job WHERE employer_id='$user_id'");
        while($job = $jobs->fetch_assoc()){
            $job_id = $job['job_id'];
            $conn->query("DELETE FROM application WHERE job_id='$job_id'");
        }
        $conn->query("DELETE FROM job WHERE employer_id='$user_id'");
        $conn->query("DELETE FROM employer WHERE employer_id='$user_id'");
    }

    header("Location: admin-dashboard.php");
    exit();
}

if (isset($_GET['delete_job'])) {
    $job_id = $conn->real_escape_string($_GET['delete_job']);
    $conn->query("DELETE FROM application WHERE job_id = '$job_id'");
    $conn->query("DELETE FROM job WHERE job_id = '$job_id'");
    header("Location: admin-dashboard.php");
    exit();
}

$users_sql = "SELECT user_id AS id, name, role, 'Active' AS status FROM user 
              UNION 
              SELECT employer_id AS id, company_name AS name, 'Employer' AS role, 'Active' AS status FROM employer 
              LIMIT 5";
$users_result = $conn->query($users_sql);

$jobs_sql = "SELECT j.job_id, j.title, e.company_name, j.status FROM job j 
             JOIN employer e ON j.employer_id = e.employer_id 
             LIMIT 5";
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="design.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dashboard-body">

    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="admin-dashboard.php" class="active" style="background: #e0eafc; color: #0056b3;">Dashboard</a>
            <a href="admin-users.php">Manage Users</a>
            <a href="admin-vacancies.php">Monitor Vacancies</a>
            <a href="admin-reports.php">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <div class="dashboard-content">
            <h1>Admin Control Panel</h1>
            <table class="application-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($users_result->num_rows > 0) {
                        while($row = $users_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>
                                    <a href='admin-dashboard.php?delete_user=" . $row['id'] . "' class='apply-btn' style='background-color:#dc3545; padding: 5px 10px; text-decoration:none; font-size:0.85rem; border-radius:4px;' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <br>
            <h1>Vacancy Supervision</h1>
            <table class="application-table">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Employer</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($jobs_result->num_rows > 0) {
                        while($row = $jobs_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>
                                    <a href='admin-dashboard.php?delete_job=" . $row['job_id'] . "' class='apply-btn' style='background-color:#dc3545; padding: 5px 10px; text-decoration:none; font-size:0.85rem; border-radius:4px;' onclick='return confirm(\"Remove this vacancy?\");'>Remove</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No vacancies found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-footer">
        <p style="color:white; padding:10px;">MyKerjaConnectUTeM Admin Portal</p>
    </div>
</body>
</html>