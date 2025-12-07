<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "mahasiswa") {
    header("Location: ../index.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Mahasiswa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa | UNPERBA Coding</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #eef1f5;
            font-family: Poppins, sans-serif;
        }
        .welcome-box {
            background: linear-gradient(135deg, #0A0A60, #B00032);
            border-radius: 22px;
            padding: 45px;
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            max-width: 750px;
            margin: auto;
        }
        .welcome-box strong {
            color: #FFD700;
        }
        .menu-card {
            background: white;
            border-radius: 18px;
            padding: 30px;
            border: 2px solid #0A0A60;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.25s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .menu-card h4 {
            color: #0A0A60;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .menu-card p {
            color: #555;
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="container py-5">

        <div class="welcome-box text-center mb-4">
            <h2 class="fw-bold">Dashboard Mahasiswa</h2>
            <p class="fs-5 mt-3">Selamat datang <strong><?php echo $username; ?></strong></p>
            <p>Silakan pilih menu berikut.</p>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <a href="profil_view.php" class="menu-card">
                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                    <h4>Profil</h4>
                    <p>Lihat dan perbarui data diri</p>
                </a>
            </div>

            <!-- MENU AMBIL MATA KULIAH -->
            <div class="col-md-6">
                <a href="ambil_matkul.php" class="menu-card">
                    <i class="bi bi-journal-plus fs-2 text-primary"></i>
                    <h4>Ambil Mata Kuliah</h4>
                    <p>Pilih mata kuliah yang ingin diambil</p>
                </a>
            </div>

            <!-- KRS -->
            <div class="col-md-6">
                <a href="krs_saya.php" class="menu-card">
                    <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                    <h4>KRS Saya</h4>
                    <p>Daftar mata kuliah yang sudah diambil</p>
                </a>
            </div>

            <div class="col-md-6">
                <a href="ubah_password.php" class="menu-card" style="border-color:#B00032">
                    <i class="bi bi-key fs-2 text-danger"></i>
                    <h4>Reset Password</h4>
                    <p>Mengubah password login</p>
                </a>
            </div>

            <div class="col-md-6">
                <a href="../logout.php" class="menu-card" style="border-color:#B00032">
                    <i class="bi bi-box-arrow-right fs-2 text-danger"></i>
                    <h4>Logout</h4>
                    <p>Akhiri sesi mahasiswa</p>
                </a>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
