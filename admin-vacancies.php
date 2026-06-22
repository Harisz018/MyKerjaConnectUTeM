<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Logik proses padam pekerjaan dipindahkan ke sini
if (isset($_GET['delete_job'])) {
    $job_id = $conn->real_escape_string($_GET['delete_job']);
    $conn->query("DELETE FROM application WHERE job_id = '$job_id'");
    $conn->query("DELETE FROM job WHERE job_id = '$job_id'");
    header("Location: admin-vacancies.php");
    exit();
}

$job_search = isset($_GET['job_search']) ? $conn->real_escape_string($_GET['job_search']) : '';
$faculty_filter = isset($_GET['faculty']) ? $conn->real_escape_string($_GET['faculty']) : '';

// Mengambil data lengkap berkaitan pekerjaan dari database
$sql = "SELECT j.job_id, j.title, j.description, j.salary, j.location, j.status, e.company_name 
        FROM job j 
        JOIN employer e ON j.employer_id = e.employer_id WHERE 1=1";

if (!empty($job_search)) {
    $sql .= " AND j.title LIKE '%$job_search%'";
}

if (!empty($faculty_filter)) {
    $sql .= " AND j.location = '$faculty_filter'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage Jobs</title>
    <link rel="stylesheet" href="design.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<body class="dashboard-body">

    <?php include 'headerDashboard.php'; ?>

<div class="dashboard-container">
    <aside class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="admin-users.php">Manage Users</a>
            <a href="admin-vacancies.php" class="active" style="background: #e0eafc; color: #0056b3;">Manage Jobs</a>
            <a href="admin-reports.php">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

    <div class="dashboard-content">
        <h1>Manage Jobs</h1>

        <form method="GET" action="admin-vacancies.php" class="filter-section" style="grid-template-columns: 2fr 1fr auto;">
            <input type="text" name="job_search" placeholder="Search by Job Title" value="<?php echo htmlspecialchars($job_search); ?>">
            
            <select name="faculty">
                <option value="">All Locations / Faculties</option>
                <option value="FTMK" <?php if($faculty_filter == 'FTMK') echo 'selected'; ?>>FTMK</option>
                <option value="FTKEK" <?php if($faculty_filter == 'FTKEK') echo 'selected'; ?>>FTKEK</option>
                <option value="FTKIP" <?php if($faculty_filter == 'FTKIP') echo 'selected'; ?>>FTKIP</option>
                <option value="FTKM" <?php if($faculty_filter == 'FTKM') echo 'selected'; ?>>FTKM</option>
                <option value="FPTT" <?php if($faculty_filter == 'FPTT') echo 'selected'; ?>>FPTT</option>
                <option value="FTKE" <?php if($faculty_filter == 'FTKE') echo 'selected'; ?>>FTKE</option>
                <option value="FAIX" <?php if($faculty_filter == 'FAIX') echo 'selected'; ?>>FAIX</option>
            </select>
            
            <button type="submit" style="padding: 12px 25px;">Filter</button>
        </form>

        <table class="application-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Employer</th>
                    <th>Location/Faculty</th>
                    <th>Rate (RM/H)</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['company_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>RM " . htmlspecialchars($row['salary']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <a href='admin-vacancies.php?delete_job=" . $row['job_id'] . "' class='apply-btn' style='background-color:#dc3545; padding: 6px 12px; text-decoration:none; font-size:0.85rem; border-radius:4px;' onclick='return confirm(\"Remove this vacancy permanently?\");'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No vacancies found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<footer>
            <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
        </footer>
</body>
</html>