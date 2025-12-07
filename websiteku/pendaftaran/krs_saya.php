<?php
session_start();
include("../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "mahasiswa") {
    header("Location: ../index.php");
    exit;
}

$id_mhs = $_SESSION['user_id'];

$q_dospem = mysqli_query($db, "
    SELECT d.nama_dosen 
    FROM tb_website w
    LEFT JOIN dosen d ON d.id = w.id_dospem
    WHERE w.user_id = $id_mhs
");
$dospem = mysqli_fetch_assoc($q_dospem);
$nama_dospem = $dospem['nama_dosen'] ?? "-";

$sql = "
    SELECT 
        k.id, 
        k.status_validasi,
        m.kode_mk, 
        m.nama_mata_kuliah,
        m.sks, 
        m.jam_mulai, 
        m.jam_selesai,
        m.ruangan, 
        IFNULL(d.nama_dosen,'-') AS nama_dosen
    FROM krs k
    JOIN mata_kuliah m ON m.id = k.id_mata_kuliah
    LEFT JOIN dosen d ON d.id = m.id_dosen
    WHERE k.id_mahasiswa = $id_mhs
";

$list = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KRS Saya</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background:#eef1f5;
            font-family:Poppins, sans-serif;
        }
        .title-box {
            background: linear-gradient(135deg,#0A0A60,#B00032);
            color:white;
            padding:25px;
            border-radius:18px;
            text-align:center;
            margin-bottom:25px;
        }
        table thead th {
            background:#0A0A60;
            color:white;
        }
    </style>
</head>

<body>

<div class="container py-4">

    <div class="title-box">
        <h3 class="fw-bold">KRS Saya</h3>
        <p class="mb-0">Daftar mata kuliah yang sudah kamu ambil</p>
    </div>

    <div class="alert alert-info mb-4">
        Dosen Pembimbing Kamu:
        <strong><?= htmlspecialchars($nama_dospem); ?></strong>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <div class="alert <?= 
            $_GET['status']=='hapus_ok' ? 'alert-danger' :
            ($_GET['status']=='hapus_gagal' ? 'alert-warning' :
            ($_GET['status']=='sukses' ? 'alert-success' :
            'alert-danger')) ?>">
            <?= 
                $_GET['status']=='hapus_ok' ? 'Mata kuliah dihapus.' :
                ($_GET['status']=='hapus_gagal' ? 'Gagal menghapus mata kuliah.' :
                ($_GET['status']=='sukses' ? 'Mata kuliah berhasil ditambahkan.' :
                'Terjadi kesalahan.')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Jam</th>
                        <th>Ruang</th>
                        <th>SKS</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no=1; while($r=mysqli_fetch_assoc($list)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($r['kode_mk']); ?></td>
                        <td class="text-start"><?= htmlspecialchars($r['nama_mata_kuliah']); ?></td>
                        <td><?= htmlspecialchars($r['nama_dosen']); ?></td>

                        <td>
                            <?= $r['jam_mulai'] ? substr($r['jam_mulai'],0,5) : "-"; ?>
                            -
                            <?= $r['jam_selesai'] ? substr($r['jam_selesai'],0,5) : "-"; ?>
                        </td>

                        <td><?= htmlspecialchars($r['ruangan']); ?></td>
                        <td><?= htmlspecialchars($r['sks']); ?></td>

                        <td>
                            <?php if ($r['status_validasi'] == "menunggu"): ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php elseif ($r['status_validasi'] == "disetujui"): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>

<td>
    <?php if ($r['status_validasi'] == "disetujui"): ?>
        <span class="text-muted">Terkunci</span>
    <?php else: ?>
        <a href="krs_hapus.php?id=<?= $r['id'] ?>" 
           onclick="return confirm('Hapus mata kuliah ini dari KRS?')" 
           class="btn btn-danger btn-sm">
           Hapus
        </a>
    <?php endif; ?>
</td>


                    </tr>
                <?php endwhile; ?>
                </tbody>

            </table>
        </div>

        <a href="index.php" class="btn" style="background:#0A0A60; color:white; border-radius:8px; padding:8px 18px;">
            Kembali
        </a>

    </div>

</div>

</body>
</html>
