<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $del_id = $conn->real_escape_string($_GET['delete_id']);
    $conn->query("DELETE FROM application WHERE user_id = '$del_id'");
    $conn->query("DELETE FROM user WHERE user_id = '$del_id'");
    $conn->query("DELETE FROM employer WHERE employer_id = '$del_id'");
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
<html>
<head>
    <title>Admin User Management</title>
    <link rel="stylesheet" href="design.css">
</head>
<body class="dashboard-body">

<div class="dashboard-header">
    <h1>MyKerjaConnectUTeM</h1>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
</div>

<div class="dashboard-container">
    <div class="sidebar">
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="admin-users.php">Manage Users</a>
        <a href="admin-vacancies.php">Monitor Vacancies</a>
        <a href="admin-reports.php">System Reports</a>
        <a href="logout.php">Sign Out</a>
    </div>

    <div class="dashboard-content">
        <h1>Manage Users</h1>

        <form method="GET" action="admin-users.php" class="filter-section">
            <input type="text" name="search" placeholder="Search Name/Email" value="<?php echo htmlspecialchars($search); ?>">
            <select name="filter">
                <option value="All" <?php if($filter == 'All') echo 'selected'; ?>>All</option>
                <option value="Student" <?php if($filter == 'Student') echo 'selected'; ?>>Student</option>
                <option value="Employer" <?php if($filter == 'Employer') echo 'selected'; ?>>Employer</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <table class="application-table">
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>
                            <a href='admin-users.php?delete_id=" . $row['id'] . "' class='apply-btn' style='background-color:#dc3545; padding: 5px 10px; text-decoration:none; font-size:0.85rem;' onclick='return confirm(\"Delete this user permanently?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>