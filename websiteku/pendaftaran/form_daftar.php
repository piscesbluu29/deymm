<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");

$dosen = mysqli_query($db, "SELECT id, nama_dosen FROM dosen ORDER BY nama_dosen ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Formulir Pendaftaran</title>

<style>
    body { font-family: Arial; margin: 0; padding: 25px; background: #f4f5f7; }
    .title-box {
        background: linear-gradient(to right, #A50044, #004D98);
        padding: 18px; border-radius: 10px; color: white;
        text-align: center; font-size: 20px; font-weight: bold;
        margin-bottom: 25px;
    }
    .card {
        background: white; padding: 20px; border-radius: 10px;
        max-width: 600px; margin: auto;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    label { font-weight: bold; }
    input[type=text], textarea, select {
        width: 100%; padding: 8px; border-radius: 6px;
        border: 1px solid #ccc; margin-top: 5px; margin-bottom: 15px;
    }
    textarea { height: 80px; }
    .btn { padding: 8px 14px; background: #004D98; color: white; border-radius: 6px; border: none; cursor: pointer; }
    .btn:hover { opacity: 0.9; }
    .back { display: inline-block; margin-top: 15px; background: #444; color: white; padding: 8px 14px; text-decoration: none; border-radius: 6px; }
</style>

</head>
<body>

<div class="title-box">Formulir Pendaftaran Mahasiswa</div>

<div class="card">

<form action="proses_tambah.php" method="POST">

    <label>NIM</label>
    <input type="text" name="nim" required>

    <label>Nama</label>
    <input type="text" name="nama" required>

<label>Prodi</label>
<select name="prodi" required>
    <option value="">Pilih Prodi</option>
    <option value="Informatika">Informatika</option>
    <option value="Agribisnis">Manajemen</option>
    <option value="Teknik Mesin">Akuntansi</option>
    <option value="Manajemen">Teknik Mesin</option>
    <option value="Akuntansi">Agribisnis</option>
</select>


    <label>Dosen Pembimbing</label>
    <select name="id_dospem" required>
        <option value="">Pilih Dosen Pembimbing</option>
        <?php while($d = mysqli_fetch_assoc($dosen)): ?>
            <option value="<?= $d['id']; ?>"><?= htmlspecialchars($d['nama_dosen']); ?></option>
        <?php endwhile; ?>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" required></textarea>

    <label>Jenis Kelamin</label>
    <br>
    <label><input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki</label>
    <label><input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan</label>
    <br><br>

    <label>Agama</label>
    <select name="agama" required>
        <option value="">Pilih Agama</option>
        <?php $agama = ['Islam','Kristen','Katholik','Hindu','Buddha','Konghucu','Atheis'];
        foreach($agama as $a) echo "<option>$a</option>"; ?>
    </select>

    <label>Sekolah Asal</label>
    <input type="text" name="sekolah_asal" required>

    <label>No Hp</label>
    <input type="text" name="hp" required>

    <label>Email</label>
    <input type="text" name="email" required>

    <button type="submit" name="simpan" class="btn">Simpan</button>

</form>

<a class="back" href="list_data.php">Kembali</a>

</div>

</body>
</html>
