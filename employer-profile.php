<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['employer_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}
$employer_id = $_SESSION['employer_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company = $conn->real_escape_string($_POST['company_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone_no']);
    
    $update_sql = "UPDATE employer SET company_name='$company', email='$email', phone_no='$phone' WHERE employer_id='$employer_id'";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['name'] = $company; 
        echo "<script>alert('Profile updated successfully!');</script>";
    }
}

$sql = "SELECT * FROM employer WHERE employer_id = '$employer_id'";
$result = $conn->query($sql);
$emp_data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Profile | MyKerjaConnectUTeM</title>
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
            <a href="employer-review-apps.php">Review Apps</a>
            <a href="employer-profile.php" class="active" style="background: #e0eafc; color: #0056b3;">Profile</a>
            <a href="logout.php">Sign Out</a>
        </aside>

        <main class="dashboard-content">
            <h2>Manage Department Metadata & System Attributes</h2>

            <form action="employer-profile.php" method="POST" style="margin-top: 20px;">
                <div class="profile-grid">
                    <div class="profile-section">
                        <h3>Faculty/Company Information</h3>
                        <div class="form-group">
                            <label>Employer ID / Matric Number:</label>
                            <input type="text" value="<?php echo htmlspecialchars($emp_data['employer_id']); ?>" disabled>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label>Faculty / Company Name:</label>
                            <input type="text" name="company_name" value="<?php echo htmlspecialchars($emp_data['company_name']); ?>" required>
                        </div>
                    </div>

                    <div class="profile-section">
                        <h3>Contact & Authorize Config</h3>
                        <div class="form-group">
                            <label>Campus Email:</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($emp_data['email']); ?>" required>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label>Office Extension / Phone Number:</label>
                            <input type="text" name="phone_no" value="<?php echo htmlspecialchars($emp_data['phone_no']); ?>" placeholder="e.g. 0123456789">
                        </div>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 25px;">
                    <button type="submit" class="apply-btn" style="padding: 12px 35px;">Update Profile</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>