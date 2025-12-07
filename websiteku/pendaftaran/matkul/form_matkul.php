<?php
include("../config.php");
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

$data = [];

if ($id) {
    $stmt = mysqli_prepare($db, "SELECT * FROM mata_kuliah WHERE id = ? AND deleted_at IS NULL LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        $data = $row;
    } else {
        header("Location: list_matkul.php");
        exit;
    }
}

$prodi = mysqli_query($db, "SELECT id, nama_prodi FROM program_studi WHERE deleted_at IS NULL ORDER BY nama_prodi ASC");
$dosen = mysqli_query($db, "SELECT id, nama_dosen FROM dosen WHERE deleted_at IS NULL ORDER BY nama_dosen ASC");

$hariList = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];

$pageTitle  = $id ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah';
$formAction = $id ? 'proses_edit_matkul.php' : 'proses_tambah_matkul.php';

function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES,'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?php echo $pageTitle; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{font-family:sans-serif;background:#f4f5f7;padding:20px;}
.title-box{background:linear-gradient(to right,#A50044,#004D98);padding:18px;color:#fff;border-radius:10px;text-align:center;margin-bottom:20px;}
.card{max-width:700px;margin:auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);}
label{display:block;font-weight:700;margin-top:5px;margin-bottom:4px;color:#333;}
input,select{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin-bottom:15px;}
.btn.save{background:#0A0A60;color:#fff;}
.btn.cancel{background:#A50044;color:#fff;}
.btn:hover{opacity:0.9;color:#fff;}
</style>
</head>
<body>

<div class="title-box">
    <h3><?php echo $pageTitle; ?></h3>
</div>

<div class="card">
<form method="post" action="<?php echo $formAction; ?>">
<input type="hidden" name="id" value="<?php echo e($id); ?>">

<label>Kode Mata Kuliah</label>
<input type="text" name="kode_mk" required value="<?php echo e($data['kode_mk'] ?? ''); ?>">

<label>Nama Mata Kuliah</label>
<input type="text" name="nama_mata_kuliah" required value="<?php echo e($data['nama_mata_kuliah'] ?? ''); ?>">

<div class="row">
<div class="col-md-6">
<label>Semester</label>
<input type="number" name="semester" min="1" max="8" required value="<?php echo e($data['semester'] ?? ''); ?>">
</div>

<div class="col-md-6">
<label>SKS</label>
<input type="number" name="sks" min="1" required value="<?php echo e($data['sks'] ?? '3'); ?>">
</div>
</div>

<label>Program Studi</label>
<select name="id_program_studi" required>
<option value="">Pilih Program Studi</option>
<?php while($p=mysqli_fetch_assoc($prodi)): ?>
<option value="<?php echo e($p['id']); ?>" <?php echo (($data['id_program_studi'] ?? '')==$p['id'])?'selected':''; ?>>
<?php echo e($p['nama_prodi']); ?>
</option>
<?php endwhile; ?>
</select>

<label>Dosen Pengampu</label>
<select name="id_dosen" required>
<option value="">Pilih Dosen</option>
<?php while($d=mysqli_fetch_assoc($dosen)): ?>
<option value="<?php echo e($d['id']); ?>" <?php echo (($data['id_dosen'] ?? '')==$d['id'])?'selected':''; ?>>
<?php echo e($d['nama_dosen']); ?>
</option>
<?php endwhile; ?>
</select>

<label>Hari</label>
<select name="hari" required>
<option value="">Pilih Hari</option>
<?php foreach($hariList as $h): ?>
<option value="<?php echo e($h); ?>" <?php echo (($data['hari'] ?? '')==$h)?'selected':''; ?>>
<?php echo e($h); ?>
</option>
<?php endforeach; ?>
</select>

<div class="row">
<div class="col-md-6">
<label>Jam Mulai</label>
<input type="time" name="jam_mulai" value="<?php echo e($data['jam_mulai'] ?? ''); ?>">
</div>

<div class="col-md-6">
<label>Jam Selesai</label>
<input type="time" name="jam_selesai" value="<?php echo e($data['jam_selesai'] ?? ''); ?>">
</div>
</div>

<label>Ruangan</label>
<input type="text" name="ruangan" value="<?php echo e($data['ruangan'] ?? ''); ?>">

<div class="d-flex justify-content-end gap-2 mt-4">
<a href="list_matkul.php" class="btn cancel">Kembali</a>
<button type="submit" name="simpan" class="btn save">Simpan</button>
</div>
</form>
</div>

</body>
</html>
