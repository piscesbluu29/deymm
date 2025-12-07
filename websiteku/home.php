<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h2 class="logo">Katarinabluu</h2>
        <nav class="navigation">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php" class="btnLogin-popup">Logout</a>
        </nav>
    </header>

    <div class="content">
        <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <p>Kamu berhasil login ke sistem.</p>
    </div>
</body>
</html>
