<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "dosen") {
    header("Location: ../index.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Dosen';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dosen | UNPERBA Coding</title>

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
            <h2 class="fw-bold">Dashboard Dosen</h2>
            <p class="fs-5 mt-3">Selamat datang <strong><?php echo $username; ?></strong></p>
            <p>Silakan pilih menu berikut.</p>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <a href="../pengampu/list_pengampu.php" class="menu-card">
                    <i class="bi bi-journal-text fs-2 text-primary"></i>
                    <h4>Kelola Perkuliahan</h4>
                    <p>Atur jadwal dan data pengampu</p>
                </a>
            </div>

            <div class="col-md-6">
                <a href="profil_view_dosen.php" class="menu-card">
                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                    <h4>Profil Dosen</h4>
                    <p>Lihat dan perbarui data diri</p>
                </a>
            </div>

            <!-- MENU VALIDASI KRS -->
            <div class="col-md-6">
                <a href="validasi_krs.php" class="menu-card">
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                    <h4>Validasi KRS</h4>
                    <p>Validasi mata kuliah mahasiswa</p>
                </a>
            </div>

            <div class="col-md-6">
                <a href="ubah_password.php" class="menu-card" style="border-color:#B00032">
                    <i class="bi bi-key fs-2 text-danger"></i>
                    <h4>Reset Password</h4>
                    <p>Mengubah Password Login</p>
                </a>
            </div>

            <div class="col-md-6">
                <a href="../../logout.php" class="menu-card" style="border-color:#B00032">
                    <i class="bi bi-box-arrow-right fs-2 text-danger"></i>
                    <h4>Logout</h4>
                    <p>Akhiri sesi dosen</p>
                </a>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
