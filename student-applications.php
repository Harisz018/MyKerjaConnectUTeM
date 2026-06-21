<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['proof_document']) && isset($_POST['app_id'])) {
    $app_id = $conn->real_escape_string($_POST['app_id']);
    
    $target_dir = "uploads/";
    $file_extension = strtolower(pathinfo($_FILES["proof_document"]["name"], PATHINFO_EXTENSION));
    $new_filename = $user_id . "_" . $app_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if ($file_extension === "pdf") {
        if ($_FILES["proof_document"]["size"] <= 524288000) {
            if (move_uploaded_file($_FILES["proof_document"]["tmp_name"], $target_file)) {
                $sql_insert = "INSERT INTO work_proof (application_id, file_name) VALUES ('$app_id', '$new_filename')";
                if ($conn->query($sql_insert) === TRUE) {
                    echo "<script>alert('Proof of work uploaded successfully!'); window.location.href='student-applications.php';</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('Upload failed: File size exceeds the 500 MB limit.');</script>";
        }
    } else {
        echo "<script>alert('Upload failed: Only PDF files are allowed.');</script>";
    }
}

$sql = "SELECT a.application_id, a.apply_date, a.status, a.payment_status, wp.file_name as proof_file, j.title, j.location, e.company_name 
        FROM application a 
        JOIN job j ON a.job_id = j.job_id 
        JOIN employer e ON j.employer_id = e.employer_id 
        LEFT JOIN work_proof wp ON a.application_id = wp.application_id
        WHERE a.user_id = '$user_id' 
        ORDER BY a.apply_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Applications | MyKerjaConnect UTeM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

    <header class="dashboard-header">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <div class="logo">MyKerjaConnectUTeM</div>
        <div id="welcomeMessage">Welcome, <?php echo htmlspecialchars($name); ?></div>
    </header>

    <div class="dashboard-container">
        <nav class="sidebar" id="sidebar">
            <a href="student-dashboard.php">Dashboard</a>
            <a href="student-browsejobs.php">Browse Jobs</a>
            <a href="student-applications.php" class="active" style="background: #e0eafc; color: #0056b3;">Applications</a>
            <a href="student-profile.php">Profile</a>
            <a href="logout.php" id="signOutBtn">Sign Out</a>
        </nav>

        <main class="dashboard-content">
            <h2>Track Your Applications</h2>
            
            <div class="updates-box">
                <table class="application-table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Employer / Faculty</th>
                            <th>Date Applied</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                            <th>Proof of Work (PDF)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['company_name']) . " (" . htmlspecialchars($row['location']) . ")</td>";
                                echo "<td>" . htmlspecialchars($row['apply_date']) . "</td>";
                                
                                $status_color = "#333";
                                if($row['status'] == 'Approved') $status_color = "#28a745";
                                if($row['status'] == 'Rejected') $status_color = "#dc3545";
                                echo "<td style='color: {$status_color}; font-weight: 600;'>" . htmlspecialchars($row['status']) . "</td>";
                                
                                $pay_color = "#dc3545";
                                $pay_text = "Unpaid";
                                if($row['payment_status'] == 'Paid') {
                                    $pay_color = "#28a745";
                                    $pay_text = "Paid";
                                }
                                echo "<td style='color: {$pay_color}; font-weight: 600;'>" . $pay_text . "</td>";

                                echo "<td>";
                                if ($row['status'] == 'Approved') {
                                    if (!empty($row['proof_file'])) {
                                        echo "<a href='uploads/" . htmlspecialchars($row['proof_file']) . "' target='_blank' style='color:#0056b3; font-weight:bold; text-decoration:none;'>View PDF</a>";
                                    } else {
                                        echo "<form action='student-applications.php' method='POST' enctype='multipart/form-data' style='display:flex; flex-direction:column; gap:5px; align-items:flex-start;'>
                                                <input type='hidden' name='app_id' value='" . $row['application_id'] . "'>
                                                <input type='file' name='proof_document' accept='.pdf' required style='font-size:0.8rem; width:180px;'>
                                                <span style='font-size:0.75rem; color:#666;'>Max size: 500MB</span>
                                                <button type='submit' class='apply-btn' style='padding:5px 10px; font-size:0.8rem;'>Upload</button>
                                              </form>";
                                    }
                                } else {
                                    echo "<span style='color:#999; font-size:0.9rem;'>Available upon approval</span>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>You have not applied for any jobs yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>