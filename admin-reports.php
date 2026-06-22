<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$u_count = $conn->query("SELECT COUNT(*) AS total FROM user")->fetch_assoc()['total'];
$e_count = $conn->query("SELECT COUNT(*) AS total FROM employer")->fetch_assoc()['total'];
$total_users = $u_count + $e_count;

$total_jobs = $conn->query("SELECT COUNT(*) AS total FROM job WHERE status='Active'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin System Reports</title>
    <link rel="stylesheet" href="design.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body class="dashboard-body">

<header class="dashboard-header">
    <div class="logo">MyKerjaConnectUTeM</div>
    <div style="font-weight: 600;">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></div>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
            <a href="admin-dashboard.php">Dashboard</a>
            <a href="admin-users.php">Manage Users</a>
            <a href="admin-vacancies.php">Monitor Vacancies</a>
            <a href="admin-reports.php" class="active" style="background: #e0eafc; color: #0056b3;">System Reports</a>
            <a href="logout.php">Sign Out</a>
        </aside>

    <div class="dashboard-content">
        
        <div id="report-content" style="padding: 30px; background: white; border-radius: 10px; border: 1px solid #ddd;">
            <h1 style="text-align: center; color: #0056b3; margin-bottom: 10px;">Laporan Sistem MyKerjaConnect UTeM</h1>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">Tarikh Janaan: <?php echo date('d F Y, H:i A'); ?></p>
            <hr style="margin-bottom: 30px; border: 1px solid #eee;">
            
            <h2 style="margin-bottom: 15px;">Ringkasan Data (Summary Dashboard)</h2>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <p style="font-size: 1.1rem; margin-bottom: 10px;"><b>Jumlah Pengguna Berdaftar (Pelajar & Majikan):</b> <?php echo $total_users; ?> pengguna</p>
                <p style="font-size: 1.1rem;"><b>Jumlah Pekerjaan Aktif (Active Vacancies):</b> <?php echo $total_jobs; ?> jawatan</p>
            </div>
            
            <br>
            <p style="text-align: center; font-size: 0.9rem; color: #aaa; margin-top: 40px;">Laporan ini dijana secara automatik oleh sistem MyKerjaConnect UTeM.</p>
        </div>

        <br><br>
        <h1>Report Generation Options</h1>
        <div class="updates-box">
            <p><b>Report Format:</b> PDF</p>
            <p>Data metrics are synchronised with your Apache server MySQL deployment.</p>
        </div>
        
        <button class="apply-btn" onclick="generatePDF()" style="margin-top: 20px; padding: 12px 25px;">Generate & Export Report (PDF)</button>
        
    </div>
</div>

<script>
    function generatePDF() {
        var element = document.getElementById('report-content');
        var dashboardContent = document.querySelector('.dashboard-content');
        var dashboardBody = document.querySelector('.dashboard-body');
        
        var origOverflow = dashboardContent.style.overflowY;
        var origHeight = dashboardBody.style.height;

        dashboardContent.style.overflowY = 'visible';
        dashboardBody.style.height = 'auto';

        var opt = {
            margin:       0.5,
            filename:     'Laporan_MyKerjaConnect.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, scrollY: 0 },
            jsPDF:        { unit: 'in', format: 'A4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(function() {
            dashboardContent.style.overflowY = origOverflow;
            dashboardBody.style.height = origHeight;
        });
    }
</script>

</body>
</html>