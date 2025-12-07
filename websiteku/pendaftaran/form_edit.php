<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");

if (!isset($_GET['id'])) die("Akses tidak valid.");

$id = intval($_GET['id']);
$q = mysqli_query($db, "SELECT * FROM tb_website WHERE id=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) die("Data tidak ditemukan.");

$dosen = mysqli_query($db, "SELECT id, nama_dosen FROM dosen ORDER BY nama_dosen ASC");

function safe($v){
    return htmlspecialchars($v ?? '', ENT_QUOTES);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background:#f4f5f7; padding:25px; font-family:Arial; }
    .title-box {
        background:linear-gradient(to right,#A50044,#004D98);
        padding:18px; border-radius:10px; color:white;
        text-align:center; margin-bottom:25px; font-size:20px;
        font-weight:bold;
    }
    .card { background:white; padding:20px; border-radius:10px; max-width:600px; margin:auto; box-shadow:0 3px 8px rgba(0,0,0,0.1); }
    label { font-weight:bold; margin-top:10px; }
</style>

</head>
<body>

<div class="title-box">Edit Data Mahasiswa</div>

<div class="card">

<form action="proses_edit.php" method="POST">

    <input type="hidden" name="id" value="<?= safe($data['id']); ?>">

    <label>NIM</label>
    <input type="text" class="form-control" name="nim" value="<?= safe($data['nim']); ?>" required>

    <label>Nama</label>
    <input type="text" class="form-control" name="nama" value="<?= safe($data['nama']); ?>" required>

    <label>Prodi</label>
    <select name="prodi" class="form-control" required>
        <option value="">Pilih Prodi</option>

        <?php
        $listProdi = ['Informatika','Agribisnis','Teknik Mesin','Manajemen','Akuntansi'];

        foreach ($listProdi as $p) {
            $sel = ($data['prodi'] == $p) ? 'selected' : '';
            echo "<option value='$p' $sel>$p</option>";
        }
        ?>
    </select>

    <label>Dosen Pembimbing</label>
    <select name="id_dospem" class="form-control" required>
        <option value="">Pilih Dosen Pembimbing</option>

        <?php
        // Cek apakah dosen lama masih ada di tabel
        $cekDosen = mysqli_query($db, "SELECT id FROM dosen WHERE id=" . intval($data['id_dospem']));
        $dosenMasihAda = mysqli_num_rows($cekDosen) > 0;

        // Tampilkan dosen yang ada saat ini
        mysqli_data_seek($dosen, 0);
        while ($d = mysqli_fetch_assoc($dosen)):
            $sel = ($data['id_dospem'] == $d['id']) ? 'selected' : '';
            echo "<option value='{$d['id']}' $sel>" . safe($d['nama_dosen']) . "</option>";
        endwhile;
        ?>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required><?= safe($data['alamat']); ?></textarea>

    <label>Jenis Kelamin</label>
    <div class="mt-1">
        <label class="me-3">
            <input type="radio" name="jenis_kelamin" value="Laki-laki" <?= $data['jenis_kelamin']=='Laki-laki'?'checked':''; ?>> Laki-laki
        </label>
        <label>
            <input type="radio" name="jenis_kelamin" value="Perempuan" <?= $data['jenis_kelamin']=='Perempuan'?'checked':''; ?>> Perempuan
        </label>
    </div>

    <label>Agama</label>
    <select name="agama" class="form-control" required>
        <?php
        $agama = ['Islam','Kristen','Katholik','Hindu','Buddha','Konghucu','Atheis'];
        foreach($agama as $a){
            $sel = ($data['agama']==$a) ? 'selected' : '';
            echo "<option value='$a' $sel>$a</option>";
        }
        ?>
    </select>

    <label>Sekolah Asal</label>
    <input type="text" class="form-control" name="sekolah_asal" value="<?= safe($data['sekolah_asal']); ?>" required>

    <label>No HP</label>
    <input type="text" class="form-control" name="hp" value="<?= safe($data['hp']); ?>" required>

    <label>Email</label>
    <input type="email" class="form-control" name="email" value="<?= safe($data['email']); ?>" required>

    <button type="submit" class="btn btn-primary mt-3 w-100">Simpan</button>

</form>

<a href="list_data.php" class="btn mt-2 w-100" style="background:#A50044;color:white;">Kembali</a>

</div>

</body>
</html>
