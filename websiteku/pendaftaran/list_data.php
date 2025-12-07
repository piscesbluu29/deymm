<?php
include("config.php");

$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';

$sql = "
    SELECT 
        m.*,
        d.nama_dosen AS nama_dospem
    FROM tb_website m
    LEFT JOIN dosen d ON d.id = m.id_dospem
    WHERE 
        (
            m.nim LIKE '%$keyword%' 
            OR m.nama LIKE '%$keyword%' 
            OR m.email LIKE '%$keyword%' 
            OR m.hp LIKE '%$keyword%'
            OR d.nama_dosen LIKE '%$keyword%'
        )
        AND m.deleted_at IS NULL
";

$q = mysqli_query($db, $sql);
$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f5f7;
            font-family: Poppins, sans-serif;
        }
        .title-box {
            background: linear-gradient(135deg, #A50044, #004D98);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            color: white;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
        }
        .btn-custom {
            background: #004D98;
            color: white;
            border-radius: 6px;
        }
        .btn-custom:hover {
            background: #003575;
        }
        .btn-red {
            background: #A50044;
            color: white;
            border-radius: 6px;
        }
        .btn-red:hover {
            background: #7a0033;
        }
        .table thead th {
            background: #004D98;
            color: white;
            text-align: center;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: #eef4ff;
        }
    </style>
</head>
<body class="p-4">

<div class="container">

    <div class="title-box">
        Daftar Mahasiswa
    </div>

    <div class="card p-3 mb-3 shadow-sm">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input 
                type="text" 
                name="cari"
                placeholder="Cari mahasiswa"
                value="<?php echo htmlspecialchars($keyword); ?>"
                class="form-control"
                style="max-width: 260px;"
            >
            <button class="btn btn-custom">Cari</button>

            <a href="form_daftar.php" class="btn btn-custom">Tambah</a>
            <a href="admin/index.php" class="btn btn-red">Kembali</a>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive p-2">

            <table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Dosen Pembimbing</th>
            <th>Alamat</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Sekolah Asal</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($row = mysqli_fetch_assoc($q)) { ?>
        <tr>
            <td class="text-center"><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['nim']); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>

            <td>
                <?php echo $row['prodi'] 
                    ? htmlspecialchars($row['prodi']) 
                    : '<span class="text-muted fst-italic">Belum diisi</span>'; ?>
            </td>

            <td>
                <?php echo $row['nama_dospem'] 
                    ? htmlspecialchars($row['nama_dospem']) 
                    : '<span class="text-muted fst-italic">Belum dipilih</span>'; ?>
            </td>

            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
            <td><?php echo htmlspecialchars($row['agama']); ?></td>
            <td><?php echo htmlspecialchars($row['sekolah_asal']); ?></td>
            <td><?php echo htmlspecialchars($row['hp']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td class="text-center">
                <div class="d-flex flex-column gap-1">
                    <a class="btn btn-custom btn-sm" href="form_edit.php?id=<?php echo $row['id']; ?>">Edit</a>

                    <a class="btn btn-red btn-sm"
                       href="hapus.php?id=<?php echo $row['id']; ?>&table=tb_website"
                       onclick="return confirm('Yakin hapus?')">Hapus</a>
                </div>
            </td>
        </tr>
    <?php } ?>
    </tbody>

</table>


        </div>
    </div>

</div>

</body>
</html>
