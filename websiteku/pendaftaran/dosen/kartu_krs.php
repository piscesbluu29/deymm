<?php
session_start();
include("../../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "dosen") {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Mahasiswa tidak ditemukan");
}

$id_mhs = intval($_GET['id']);

$bio = mysqli_fetch_assoc(mysqli_query($db, "
    SELECT nama, nim, prodi 
    FROM tb_website 
    WHERE id = $id_mhs
"));

$list = mysqli_query($db, "
    SELECT m.kode_mk, m.nama_mata_kuliah, m.sks,
           k.status_validasi
    FROM krs k
    JOIN mata_kuliah m ON m.id = k.id_mata_kuliah
    WHERE k.id_mahasiswa = $id_mhs
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Kartu KRS Mahasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="card shadow p-4" style="max-width: 700px; margin:auto;">
    <h3 class="fw-bold text-center mb-4">Kartu Rencana Studi</h3>

    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <td><?= htmlspecialchars($bio['nama']); ?></td>
        </tr>
        <tr>
            <th>NIM</th>
            <td><?= htmlspecialchars($bio['nim']); ?></td>
        </tr>
        <tr>
            <th>Program Studi</th>
            <td><?= htmlspecialchars($bio['prodi']); ?></td>
        </tr>
    </table>

    <hr>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($r=mysqli_fetch_assoc($list)): ?>
            <tr>
                <td><?= $r['kode_mk']; ?></td>
                <td><?= $r['nama_mata_kuliah']; ?></td>
                <td><?= $r['sks']; ?></td>
                <td>
                    <?php if ($r['status_validasi'] == "menunggu"): ?>
                        <span class="badge bg-warning">Menunggu</span>
                    <?php elseif ($r['status_validasi'] == "disetujui"): ?>
                        <span class="badge bg-success">Disetujui</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Ditolak</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="validasi_krs.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

</body>
</html>
