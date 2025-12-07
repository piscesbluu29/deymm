<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../../index.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | UNPERBA Coding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(180deg, #d8dcff, #eef0ff);
            font-family: Poppins, sans-serif;
        }
        .content {
            padding: 40px 25px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #0A0A60, #B00032);
            border-radius: 22px;
            padding: 45px;
            color: white;
            box-shadow: 0 15px 30px rgba(0,0,0,0.25);
            max-width: 750px;
            margin: auto;
        }
        .welcome-box strong {
            color: #FFD700;
        }
        .info-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            border-radius: 18px;
            padding: 30px;
            border: 2px solid #0A0A60;
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
            text-align: center;
            transition: 0.25s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
        .info-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 26px rgba(0,0,0,0.18);
        }
        .info-card h4 {
            color: #0A0A60;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .info-card p {
            color: #444;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="content">

        <div class="welcome-box text-center">
            <h2 class="fw-bold">Dashboard Admin</h2>
            <p class="fs-5 mt-3">Selamat datang <strong><?php echo $username; ?></strong></p>
            <p>Pilih menu yang tersedia di bawah.</p>
        </div>

        <div class="container grid-info mt-4">

            <div class="row g-4">

                <div class="col-md-4">
                    <a href="../admin/list_user.php" class="info-card">
                        <i class="bi bi-person-gear fs-2 text-primary"></i>
                        <h4>User</h4>
                        <p>Kelola akun sistem</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="../dosen/list_dosen.php" class="info-card">
                        <i class="bi bi-person-badge fs-2 text-danger"></i>
                        <h4>Dosen</h4>
                        <p>Data tenaga pendidik</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="../list_data.php" class="info-card">
                        <i class="bi bi-people fs-2 text-warning"></i>
                        <h4>Mahasiswa</h4>
                        <p>Data peserta didik</p>
                    </a>
                </div>

            </div>

            <div class="row g-4 mt-4">

                <div class="col-md-6">
                    <a href="../matkul/list_matkul.php" class="info-card">
                        <i class="bi bi-journal-bookmark fs-2 text-primary"></i>
                        <h4>Mata Kuliah</h4>
                        <p>Kelola daftar matkul</p>
                    </a>
                </div>

                <div class="col-md-6">
                    <a href="../studi/list_program_studi.php" class="info-card">
                        <i class="bi bi-building fs-2 text-danger"></i>
                        <h4>Fakultas</h4>
                        <p>Pengaturan data fakultas</p>
                    </a>
                </div>

            </div>

            <div class="row g-4 mt-1">
                <div class="col-12">
                    <a href="../../logout.php" class="info-card" style="border-color:#b00032">
                        <i class="bi bi-box-arrow-right fs-2 text-danger"></i>
                        <h4>Keluar</h4>
                        <p>Akhiri sesi admin</p>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
