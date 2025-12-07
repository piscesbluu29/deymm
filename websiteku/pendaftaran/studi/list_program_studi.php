<?php

include("../config.php");

$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';

$sql = "
    SELECT *
    FROM program_studi
    WHERE 
        (
            nama_prodi LIKE '%$keyword%' 
            OR nama_fakultas LIKE '%$keyword%'
        )
        AND deleted_at IS NULL
    ORDER BY id ASC
";

$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Program Studi | UNPERBA Coding</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f5f5;
            font-family: Poppins, sans-serif;
        }

        .title-box {
            background: linear-gradient(to right, #0A0A60, #B00032);
            padding: 25px;
            border-radius: 16px;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .toolbar-box {
            background: white;
            padding: 18px;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.15);
        }

        .btn-custom {
            background: #004D98;
            color: white;
            border-radius: 8px;
        }
        .btn-custom:hover {
            background: #003575;
        }

        .btn-red {
            background: #A50044;
            color: white;
            border-radius: 8px;
        }
        .btn-red:hover {
            background: #7a0033;
        }

        table.data-prodi-table thead tr th {
            background-color: #004D98 !important;
            color: white !important;
            font-weight: bold;
            text-align: center;
        }

        .box {
            background: white;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="title-box">
        <h3 class="fw-bold">Data Program Studi</h3>
    </div>

    <!-- Toolbar -->
    <div class="toolbar-box mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">

            <input 
                type="text"
                name="cari"
                form="searchForm"
                placeholder="Cari prodi"
                value="<?php echo htmlspecialchars($keyword); ?>"
                class="form-control"
                style="max-width: 250px;"
            >

            <form method="GET" id="searchForm">
                <input type="hidden" name="cari" id="hiddenSearch">
            </form>

            <button class="btn btn-custom"
                onclick="document.getElementById('hiddenSearch').value = document.querySelector('[name=cari]').value; document.getElementById('searchForm').submit();">
                Cari
            </button>

            <a href="form_program_studi.php" class="btn btn-custom">Tambah</a>
            <a href="../admin/index.php" class="btn btn-red">Kembali</a>

        </div>
    </div>

    <div class="box">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle data-prodi-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Prodi</th>
                        <th>Fakultas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_prodi']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_fakultas']); ?></td>
                        <td>
                            <a href="form_program_studi.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-custom">Edit</a>
                            <a 
                                href="hapus_program_studi.php?id=<?php echo $row['id']; ?>"
                                class="btn btn-sm btn-red"
                                onclick="return confirm('Yakin ingin menghapus?')"
                            >
                                Hapus
                            </a>
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
