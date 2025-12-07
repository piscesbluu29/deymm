<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$sql = "
    SELECT
        p.id,
        w.nim AS nim_mahasiswa,
        w.nama AS nama_mahasiswa,
        mk.nama_mata_kuliah AS nama_mk,
        d.nama_dosen AS nama_dosen,
        p.semester,
        p.hari,
        p.waktu_mulai,
        p.waktu_selesai
    FROM perkuliahan p
    LEFT JOIN tb_website w ON p.id_mahasiswa = w.id
    LEFT JOIN mata_kuliah mk ON p.id_mata_kuliah = mk.id
    LEFT JOIN dosen d ON p.id_dosen = d.id
    WHERE p.deleted_at IS NULL
    ORDER BY p.id ASC
";

$result = mysqli_query($db, $sql);
$no = 1;
?>
<style>
    body {
        font-family: Arial;
        margin: 0;
        padding: 25px;
        background: #f4f5f7;
    }

    .title-box {
        background: linear-gradient(to right, #A50044, #004D98);
        padding: 18px;
        border-radius: 10px;
        color: white;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background: #004D98;
        color: white;
        padding: 10px;
        text-align: left;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background: #f0f4ff;
    }

    .btn {
        padding: 7px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        background: #004D98;
        font-size: 14px;
    }

    .btn.red {
        background: #A50044;
    }

    .btn-add {
        padding: 8px 14px;
        background: #004D98;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        display: inline-block;
        margin-bottom: 20px;
    }

    .back {
        margin-top: 20px;
        display: inline-block;
        padding: 8px 14px;
        background: #444;
        color: white;
        border-radius: 6px;
        text-decoration: none;
    }
</style>

<div class="title-box">Data Perkuliahan</div>

<div class="card">

<a href="form_perkuliahan.php" class="btn-add">Tambah Perkuliahan</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Mahasiswa</th>
            <th>Mata Kuliah</th>
            <th>Dosen</th>
            <th>Semester</th>
            <th>Hari</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['nim_mahasiswa']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
            <td><?php echo htmlspecialchars($row['semester']); ?></td>
            <td><?php echo htmlspecialchars($row['hari']); ?></td>
            <td><?php echo htmlspecialchars($row['waktu_mulai']); ?></td>
            <td><?php echo htmlspecialchars($row['waktu_selesai']); ?></td>
            <td>
                <a class="btn red"
                   href="hapus_perkuliahan.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Yakin hapus?')">
                   Hapus
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</div>

<a href="../index.php" class="back">Kembali</a>
