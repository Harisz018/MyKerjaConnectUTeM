<?php
session_start();
require 'db_connect.php';

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    $jobs_sql = "SELECT * FROM job WHERE status='Active' AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%') LIMIT 3";
} else {
    $jobs_sql = "SELECT * FROM job WHERE status='Active' ORDER BY job_id DESC LIMIT 3";
}
$jobs_result = $conn->query($jobs_sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>

    <?php include 'headerPublic.php'; ?>

    <main>
        <section class="search-section">
            <h1>Find Your Part-Time Jobs</h1>
            <form action="index.php" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search part-time jobs..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </section>

        <section class="categories">
            <?php
            if ($jobs_result->num_rows > 0) {
                while ($row = $jobs_result->fetch_assoc()) {
                    echo '<a href="login.php" class="card" onclick="promptLogin(event)">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p style="font-weight:normal; margin-top:10px; font-size:0.9rem;">' . htmlspecialchars($row['location']) . '</p>';
                    echo '</a>';
                }
            } else {
                echo "<p style='grid-column: span 3; text-align:center;'>No active jobs found.</p>";
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="about.php">About Us</a> | <a href="#" onclick="alert('MyKerjaConnectUTeM\n\nEmail: mykerjaconnect@utem.edu.my\nPhone: 06-1234567'); return false;">Contact Us</a></p>
    </footer>

</body>

</html>

<script>
    let isLoggedIn = <?php echo isset($_SESSION['role']) ? 'true' : 'false'; ?>;

    function promptLogin(event) {
        if (!isLoggedIn) {
            event.preventDefault();
            const userAction = confirm("To view full job details and apply, please log in or register your account.");
            if (userAction) {
                window.location.href = "login.php";
            }
        } else {
            event.preventDefault();
            window.location.href = "student-browsejobs.php";
        }
    }
</script>

</body>

</html>