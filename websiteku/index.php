<?php
include 'config.php';
session_start();

$cek_admin = mysqli_query($db, "SELECT id FROM tb_user WHERE role='admin' LIMIT 1");
$admin_ada = mysqli_num_rows($cek_admin) > 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNPERBA Coding</title>
    <link rel="stylesheet" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="video-background">
        <video autoplay loop muted>
            <source src="video/sachiro.mp4" type="video/mp4">
        </video>
    </div>
    <div class="overlay"></div>

    <header>
        <h2 class="logo">UNPERBA Coding</h2>
        <nav class="navigation">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close"></ion-icon></span>

        <!-- LOGIN -->
        <div class="form-box login">
            <h2>Login</h2>

            <?php if(isset($_SESSION['error'])) { ?>
                <div style="
                    background: #ff4d4d;
                    padding: 10px;
                    color: white;
                    margin-bottom: 15px;
                    border-radius: 6px;
                    text-align:center;
                    font-size:14px;
                ">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>

            <form action="proses_login.php" method="POST">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>

                <button type="submit" name="login" class="btn">Login</button>

                <?php if (!$admin_ada) { ?>
                <div class="login-register">
                    <p>Belum punya akun? <a href="#" class="register-link">Register</a></p>
                </div>
                <?php } ?>
            </form>
        </div>

        <!-- REGISTER (Admin pertama saja) -->
        <div class="form-box register">
            <h2>Register Admin</h2>

            <?php if (!$admin_ada) { ?>
            <form action="proses_register.php" method="POST">

                <div class="input-box">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>

                <input type="hidden" name="role" value="admin">

                <button type="submit" name="register" class="btn">Register</button>

            </form>
            <?php } ?>
        </div>

    </div>

    <script>
        const wrapper = document.querySelector('.wrapper');
        const registerLink = document.querySelector('.register-link');
        const iconClose = document.querySelector('.icon-close');

        if (registerLink) {
            registerLink.onclick = () => {
                wrapper.classList.add('active');
            };
        }

        iconClose.onclick = () => {
            wrapper.classList.remove('active');
        };
    </script>

</body>
</html>
