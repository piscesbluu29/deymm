<?php
include("../config.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$keyword = isset($_GET['cari']) ? trim($_GET['cari']) : '';

$sql = "
    SELECT
        m.id,
        m.kode_mk,
        m.nama_mata_kuliah,
        m.semester,
        m.jam_mulai,
        m.jam_selesai,
        m.ruangan,
        m.sks,
        m.hari,
        d.nama_dosen,
        ps.nama_prodi
    FROM mata_kuliah m
    LEFT JOIN dosen d ON d.id = m.id_dosen
    LEFT JOIN program_studi ps ON ps.id = m.id_program_studi
    WHERE m.deleted_at IS NULL
        AND (m.kode_mk LIKE ?
            OR m.nama_mata_kuliah LIKE ?
            OR m.semester LIKE ?
            OR ps.nama_prodi LIKE ?
            OR d.nama_dosen LIKE ?
            OR m.hari LIKE ?
        )
    ORDER BY m.kode_mk ASC
";

$like = "%$keyword%";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "ssssss", $like, $like, $like, $like, $like, $like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mata Kuliah | Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f5f5f5; font-family:Poppins, sans-serif; }
        .title-box {
            background:linear-gradient(to right,#0A0A60,#B00032);
            padding:25px;
            border-radius:16px;
            color:#fff;
            text-align:center;
            margin-bottom:20px;
        }
        .box {
            background:#fff;
            padding:20px;
            border-radius:12px;
            border:1px solid rgba(0,0,0,0.08);
        }
        table thead th {
            background:#004D98;
            color:#fff;
            text-align:center;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <div class="title-box">
        <h3 class="fw-bold">Data Mata Kuliah</h3>
    </div>

    <div class="d-flex gap-2 mb-3">

        <form method="GET" class="d-flex gap-2" id="searchForm">
            <input 
                type="text" 
                name="cari" 
                class="form-control"
                placeholder="Cari mata kuliah"
                value="<?php echo htmlspecialchars($keyword); ?>" 
                style="max-width:300px;">
            <button class="btn btn-primary">Cari</button>
        </form>

        <a href="form_matkul.php" class="btn btn-primary">Tambah</a>
        <a href="../admin/index.php" class="btn btn-danger">Kembali</a>
    </div>

    <div class="box">
        <div class="table-responsive">

            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>SKS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) :

                    $kode = $row['kode_mk'] ?? '-';
                    $nama = $row['nama_mata_kuliah'] ?? '-';
                    $dosen = $row['nama_dosen'] ?? '-';
                    $prodi = $row['nama_prodi'] ?? '-';
                    $semester = $row['semester'] ?? '-';
                    $hari = $row['hari'] ?? '-';

                    $jam_mulai = $row['jam_mulai'] ? substr($row['jam_mulai'], 0, 5) : '-';
                    $jam_selesai = $row['jam_selesai'] ? substr($row['jam_selesai'], 0, 5) : '-';
                    $jam = $jam_mulai . " - " . $jam_selesai;

                    $ruangan = $row['ruangan'] ?? '-';
                    $sks = $row['sks'] ?? '-';

                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($kode); ?></td>
                        <td class="text-start"><?php echo htmlspecialchars($nama); ?></td>
                        <td><?php echo htmlspecialchars($dosen); ?></td>
                        <td><?php echo htmlspecialchars($prodi); ?></td>
                        <td><?php echo htmlspecialchars($semester); ?></td>
                        <td><?php echo htmlspecialchars($hari); ?></td>
                        <td><?php echo htmlspecialchars($jam); ?></td>
                        <td><?php echo htmlspecialchars($ruangan); ?></td>
                        <td><?php echo htmlspecialchars($sks); ?></td>

                        <td>
                            <a href="form_matkul.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>

                            <a href="hapus_matkul.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin ingin menghapus?')">
                               Hapus
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
