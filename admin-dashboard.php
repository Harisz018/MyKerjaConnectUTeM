<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['name'];

// Mengambil semua data pengguna tanpa had (LIMIT)
$users_sql = "SELECT name, role, 'Active' AS status FROM user 
              UNION 
              SELECT company_name AS name, 'Employer' AS role, 'Active' AS status FROM employer";
$users_result = $conn->query($users_sql);

// Mengambil semua data pekerjaan tanpa had (LIMIT)
$jobs_sql = "SELECT j.title, e.company_name, j.status FROM job j 
             JOIN employer e ON j.employer_id = e.employer_id";
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="design.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body class="dashboard-body">

    <?php include 'headerDashboard.php'; ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="admin-dashboard.php" class="active" style="background: #e0eafc; color: #0056b3;">Dashboard</a>
            <a href="admin-users.php">Manage Users</a>
            <a href="admin-vacancies.php">Manage Jobs</a>
            <a href="admin-reports.php">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <div class="dashboard-content">
            <h1>Admin Control Panel</h1>

            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; border-radius: 12px; margin-top: 10px; margin-bottom: 30px;">
                <table class="application-table" style="margin: 0; box-shadow: none; border-radius: 0;">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($users_result->num_rows > 0) {
                            while ($row = $users_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No users found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h1>Vacancy Supervision</h1>

            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #eee; border-radius: 12px; margin-top: 10px;">
                <table class="application-table" style="margin: 0; box-shadow: none; border-radius: 0;">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>Job Title</th>
                            <th>Employer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($jobs_result->num_rows > 0) {
                            while ($row = $jobs_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No vacancies found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="dashboard-footer">
        <p style="color:white; padding:10px;">MyKerjaConnectUTeM Admin Portal</p>
    </div>
    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
    </footer>
</body>

</html>