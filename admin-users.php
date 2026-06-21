<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $del_id = $conn->real_escape_string($_GET['delete_id']);
    
    $check_student = $conn->query("SELECT * FROM user WHERE user_id='$del_id'");
    if ($check_student->num_rows > 0) {
        $conn->query("DELETE FROM application WHERE user_id = '$del_id'");
        $conn->query("DELETE FROM user WHERE user_id = '$del_id'");
    }

    $check_employer = $conn->query("SELECT * FROM employer WHERE employer_id='$del_id'");
    if ($check_employer->num_rows > 0) {
        $jobs = $conn->query("SELECT job_id FROM job WHERE employer_id='$del_id'");
        while($job = $jobs->fetch_assoc()){
            $job_id = $job['job_id'];
            $conn->query("DELETE FROM application WHERE job_id='$job_id'");
        }
        $conn->query("DELETE FROM job WHERE employer_id='$del_id'");
        $conn->query("DELETE FROM employer WHERE employer_id='$del_id'");
    }
    
    header("Location: admin-users.php");
    exit();
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : 'All';

$query = "SELECT user_id AS id, name, email, role FROM user WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($filter === 'Student') {
    $query .= " AND role = 'student'";
}

if ($filter === 'All' || $filter === 'Employer') {
    $emp_query = "SELECT employer_id AS id, company_name AS name, email, 'Employer' AS role FROM employer WHERE 1=1";
    if (!empty($search)) {
        $emp_query .= " AND (company_name LIKE '%$search%' OR email LIKE '%$search%')";
    }
    
    if ($filter === 'All') {
        $query = "($query) UNION ($emp_query)";
    } else {
        $query = $emp_query;
    }
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin User Management</title>
    <link rel="stylesheet" href="design.css">
</head>
<body class="dashboard-body">

    <header class="dashboard-header">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="admin-users.php" class="active" style="background: #e0eafc; color: #0056b3;">Manage Users</a>
            <a href="admin-vacancies.php">Monitor Vacancies</a>
            <a href="admin-reports.php">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <main class="dashboard-content">
            <h1 style="margin-bottom: 20px; font-size: 2.2rem; color: #333;">Manage Users</h1>

            <form method="GET" action="admin-users.php" class="filter-section">
                <input type="text" name="search" placeholder="Search Name/Email" value="<?php echo htmlspecialchars($search); ?>">
                <select name="filter">
                    <option value="All" <?php if($filter == 'All') echo 'selected'; ?>>All</option>
                    <option value="Student" <?php if($filter == 'Student') echo 'selected'; ?>>Student</option>
                    <option value="Employer" <?php if($filter == 'Employer') echo 'selected'; ?>>Employer</option>
                </select>
                <button type="submit" class="apply-btn" style="border-radius: 12px;">Filter</button>
            </form>

            <table class="application-table" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>
                                    <a href='admin-users.php?delete_id=" . $row['id'] . "' class='apply-btn' style='background-color:#dc3545; padding: 6px 12px; text-decoration:none; font-size:0.85rem; border-radius: 4px;' onclick='return confirm(\"Delete this user permanently?\");'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>