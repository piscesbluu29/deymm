<?php

session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("User belum login");
}

// Mengambil data Dosen
$sqlDosen = "SELECT id, nama_dosen FROM dosen WHERE deleted_at IS NULL ORDER BY nama_dosen ASC";
$dosen = mysqli_query($db, $sqlDosen);

// Mengambil data Mata Kuliah
$sqlMatkul = "SELECT id, nama_mata_kuliah FROM mata_kuliah WHERE deleted_at IS NULL ORDER BY nama_mata_kuliah ASC";
$matkul = mysqli_query($db, $sqlMatkul);

// Mengambil data Mahasiswa
$sqlMhs = "SELECT id, nama FROM tb_website WHERE deleted_at IS NULL ORDER BY nama ASC";
$mahasiswa = mysqli_query($db, $sqlMhs);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Perkuliahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f5f7;
        }
        .title-box {
            background: linear-gradient(to right, #A50044, #004D98);
            color: white;
            padding: 18px;
            border-radius: 10px;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .card {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body class="p-4">

<div class="title-box text-center">Tambah Perkuliahan</div>

<div class="card shadow-lg">
    <div class="card-body">
        <form method="post" action="proses_tambah_perkuliahan.php">

            <div class="mb-3">
                <label for="id_dosen" class="form-label fw-bold">Dosen</label>
                <select name="id_dosen" id="id_dosen" class="form-select" required>
                    <option value="">Pilih Dosen</option>
                    <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
                        <option value="<?php echo htmlspecialchars($d['id']); ?>"><?php echo htmlspecialchars($d['nama_dosen']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_mata_kuliah" class="form-label fw-bold">Mata Kuliah</label>
                <select name="id_mata_kuliah" id="id_mata_kuliah" class="form-select" required>
                    <option value="">Pilih Mata Kuliah</option>
                    <?php while ($m = mysqli_fetch_assoc($matkul)) { ?>
                        <option value="<?php echo htmlspecialchars($m['id']); ?>"><?php echo htmlspecialchars($m['nama_mata_kuliah']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_mahasiswa" class="form-label fw-bold">Mahasiswa</label>
                <select name="id_mahasiswa" id="id_mahasiswa" class="form-select" required>
                    <option value="">Pilih Mahasiswa</option>
                    <?php while ($w = mysqli_fetch_assoc($mahasiswa)) { ?>
                        <option value="<?php echo htmlspecialchars($w['id']); ?>"><?php echo htmlspecialchars($w['nama']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label fw-bold">Semester</label>
                <select name="semester" id="semester" class="form-select" required>
                    <option value="">Pilih Semester</option>
                    <?php for ($i=1; $i<=8; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="hari" class="form-label fw-bold">Hari</label>
                <select name="hari" id="hari" class="form-select" required>
                    <option value="">Pilih Hari</option>
                    <?php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    foreach ($days as $day) { ?>
                        <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="waktu_mulai" class="form-label fw-bold">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="waktu_selesai" class="form-label fw-bold">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <a class="btn btn-secondary" href="list_perkuliahan.php">Kembali</a>
                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>