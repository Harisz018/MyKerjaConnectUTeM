<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyKerjaConnect UTeM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">MyKerjaConnectUTeM</div>
        <nav>
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['role'])): ?>
                <a href="<?php echo $_SESSION['role']; ?>-dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <h1>Find Your Part-Time Jobs</h1>
            <div class="search-box">
                <input type="text" placeholder="Search part-time jobs...">
                <button>Search</button>
            </div>
        </section>

        <section class="categories">
            <a href="login.php" class="card" onclick="promptLogin(event)"><h3>Campus Library Assistant</h3></a>
            <a href="login.php" class="card" onclick="promptLogin(event)"><h3>Cafeteria Server</h3></a>
            <a href="login.php" class="card" onclick="promptLogin(event)"><h3>Lab Assistant</h3></a>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 MyKerjaConnect UTeM | <a href="#">Contact Us</a></p>
    </footer>

    <script>
        let isLoggedIn = <?php echo isset($_SESSION['role']) ? 'true' : 'false'; ?>; 

        function promptLogin(event) {
            if (!isLoggedIn) {
                event.preventDefault(); 
                const userAction = confirm("To view full job details and apply, please log in or register your account. Would you like to go to the login page?");
                if (userAction) {
                    window.location.href = "login.php"; 
                }
            }
        }
    </script>
</body>
</html>