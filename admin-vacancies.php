<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$job_search = isset($_GET['job_search']) ? $conn->real_escape_string($_GET['job_search']) : '';

$sql = "SELECT j.title, e.company_name, j.status FROM job j 
        JOIN employer e ON j.employer_id = e.employer_id";
if (!empty($job_search)) {
    $sql .= " WHERE j.title LIKE '%$job_search%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Monitor Vacancies</title>
    <link rel="stylesheet" href="design.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="dashboard-body">

<div class="dashboard-header">
    <h1>MyKerjaConnectUTeM</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
</div>

<div class="dashboard-container">
    <aside class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="admin-users.php">Manage Users</a>
            <a href="admin-vacancies.php" class="active" style="background: #e0eafc; color: #0056b3;">Monitor Vacancies</a>
            <a href="admin-reports.php">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

    <div class="dashboard-content">
        <h1>Monitor Vacancies</h1>

        <form method="GET" action="admin-vacancies.php" class="filter-section" style="grid-template-columns: 3fr 1fr;">
            <input type="text" name="job_search" placeholder="Search by Job Title" value="<?php echo htmlspecialchars($job_search); ?>">
            <button type="submit">Filter</button>
        </form>

        <table class="application-table">
            <tr>
                <th>Job</th>
                <th>Employer</th>
                <th>Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
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
        </table>
    </div>
</div>
</body>
</html>