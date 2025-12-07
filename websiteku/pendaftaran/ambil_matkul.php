<?php
session_start();
include("config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data mahasiswa
$q_mhs = mysqli_query($db, "
    SELECT id, nama, prodi
    FROM tb_website
    WHERE user_id = $user_id AND deleted_at IS NULL
    LIMIT 1
");

$mhs = mysqli_fetch_assoc($q_mhs);

if (!$mhs) {
    die("Data mahasiswa tidak ditemukan.");
}

$mahasiswa_id = $mhs['id'];
$prodi_nama = trim(strtolower($mhs['prodi']));

// Mapping prodi ke id_program_studi
$q_prodi = mysqli_query($db, "
    SELECT id 
    FROM program_studi
    WHERE LOWER(nama_prodi) LIKE '%$prodi_nama%'
    LIMIT 1
");

$prodiData = mysqli_fetch_assoc($q_prodi);

if (!$prodiData) {
    die("Program studi mahasiswa tidak ditemukan pada tabel program_studi.");
}

$id_program_studi = $prodiData['id'];

// Ambil mata kuliah berdasarkan id_program_studi
$q_mk = mysqli_query($db, "
    SELECT m.*, d.nama_dosen
    FROM mata_kuliah m
    LEFT JOIN dosen d ON d.id = m.id_dosen
    WHERE m.id_program_studi = $id_program_studi
    AND m.deleted_at IS NULL
    ORDER BY m.semester ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ambil Mata Kuliah | Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef1f5;
            font-family: Poppins, sans-serif;
        }
        .title-box {
            background: linear-gradient(135deg, #0A0A60, #B00032);
            padding: 25px;
            border-radius: 16px;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }
        table thead th {
            background: #004D98;
            color: white;
        }
        .btn-ambil {
            background: #0A0A60;
            color: white;
        }
        .btn-ambil:hover {
            background: #000a4a;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="title-box">
        <h3 class="fw-bold">Ambil Mata Kuliah</h3>
        <p class="mb-0">Mahasiswa: <strong><?= $mhs['nama']; ?></strong></p>
        <p class="mb-0">Program Studi: <strong><?= $mhs['prodi']; ?></strong></p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Dosen</th>
                            <th>Semester</th>
                            <th>Jam</th>
                            <th>Ruangan</th>
                            <th>SKS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($mk = mysqli_fetch_assoc($q_mk)) : 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $mk['kode_mk']; ?></td>
                            <td class="text-start"><?= $mk['nama_mata_kuliah']; ?></td>
                            <td><?= $mk['nama_dosen'] ?: '-'; ?></td>
                            <td><?= $mk['semester']; ?></td>
                            <td>
                                <?php 
                                    $jm = $mk['jam_mulai'] ? substr($mk['jam_mulai'],0,5) : "-";
                                    $js = $mk['jam_selesai'] ? substr($mk['jam_selesai'],0,5) : "-";
                                    echo "$jm - $js";
                                ?>
                            </td>
                            <td><?= $mk['ruangan'] ?: '-'; ?></td>
                            <td><?= $mk['sks']; ?></td>
                            <td>
                                <a href="proses_ambil_matkul.php?id_mk=<?= $mk['id']; ?>&id_mhs=<?= $mahasiswa_id; ?>"
                                   class="btn btn-sm btn-ambil">
                                    Ambil
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>

        </div>
    </div>

</div>

</body>
</html>
