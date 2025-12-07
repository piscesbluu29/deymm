<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($db, $_GET['cari']) : '';

$sql = "
    SELECT 
        p.id,
        d.nama_dosen,
        m.nama_mata_kuliah AS nama_mk,
        p.semester,
        p.hari,
        p.waktu_mulai AS mulai,
        p.waktu_selesai AS selesai
    FROM pengampu p
    JOIN dosen d ON p.id_dosen = d.id
    JOIN mata_kuliah m ON p.id_mata_kuliah = m.id
    WHERE (
        d.nama_dosen LIKE '%$keyword%' OR
        m.nama_mata_kuliah LIKE '%$keyword%' OR
        p.semester LIKE '%$keyword%' OR
        p.hari LIKE '%$keyword%'
    )
    AND p.deleted_at IS NULL
    ORDER BY p.id ASC
";

$result = mysqli_query($db, $sql);
$no = 1;

if (!$result) {
    die("Query Error: " . mysqli_error($db));
}
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

    .search-box {
        margin-bottom: 15px;
    }

    .search-box input {
        padding: 7px 10px;
        width: 250px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .search-box button {
        padding: 7px 12px;
        border-radius: 6px;
        background: #004D98;
        color: white;
        border: none;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
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

<div class="title-box">Data Pengampu</div>

<div class="card">

<div class="search-box">
    <form method="GET" action="">
        <input type="text" name="cari" placeholder="Cari Pengampu" value="<?php echo htmlspecialchars($keyword); ?>">
        <button type="submit">Cari</button>
    </form>
</div>

<a href="form_pengampu.php" class="btn-add">Tambah Pengampu</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Dosen</th>
            <th>Mata Kuliah</th>
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
            <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
            <td><?php echo htmlspecialchars($row['semester']); ?></td>
            <td><?php echo htmlspecialchars($row['hari']); ?></td>
            <td><?php echo htmlspecialchars($row['mulai']); ?></td>
            <td><?php echo htmlspecialchars($row['selesai']); ?></td>
            <td>
                <a class="btn red"
                   href="hapus_pengampu.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Yakin hapus?')">
                   Hapus
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</div>

<a href="../dosen/index.php" class="back">Kembali</a>
