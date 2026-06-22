<header>
    <div class="logo">
        <a href="index.php">
            <img src="logo.png" alt="MyKerjaConnect UTeM Logo" style="height: 90px; width: auto; vertical-align: middle;">
        </a>
    </div>
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