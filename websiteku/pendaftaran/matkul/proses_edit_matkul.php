<?php
session_start();
include("../config.php");

if (!isset($_POST['simpan']) && !isset($_POST['update'])) {
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

$id          = isset($_POST['id']) ? intval($_POST['id']) : 0;
$id_user     = intval($_SESSION['user_id']);
$kode_mk     = trim($_POST['kode_mk']);
$nama_mk     = trim($_POST['nama_mata_kuliah']);
$semester    = intval($_POST['semester']);
$id_prodi    = intval($_POST['id_program_studi']);
$id_dosen    = !empty($_POST['id_dosen']) ? intval($_POST['id_dosen']) : null;
$hari        = trim($_POST['hari']);
$jam_mulai   = !empty($_POST['jam_mulai']) ? $_POST['jam_mulai'] : null;
$jam_selesai = !empty($_POST['jam_selesai']) ? $_POST['jam_selesai'] : null;
$ruangan     = trim($_POST['ruangan']);
$sks         = intval($_POST['sks']);

if ($id === 0) {
    die("ID Mata Kuliah tidak valid.");
}

$sql = "
    UPDATE mata_kuliah SET
        kode_mk = ?,
        nama_mata_kuliah = ?,
        semester = ?,
        id_program_studi = ?,
        id_dosen = ?,
        hari = ?,
        jam_mulai = ?,
        jam_selesai = ?,
        ruangan = ?,
        sks = ?,
        updated_at = NOW(),
        updated_by = ?
    WHERE id = ?
";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssiiissssiii",
    $kode_mk,
    $nama_mk,
    $semester,
    $id_prodi,
    $id_dosen,
    $hari,
    $jam_mulai,
    $jam_selesai,
    $ruangan,
    $sks,
    $id_user,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: list_matkul.php?status=edit_sukses");
    exit;
}

die("Error: " . mysqli_error($db));
