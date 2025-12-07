<?php
session_start();
include("../config.php");

if (!isset($_POST["simpan"])) {
    exit;
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Akses ditolak");
}

$id_user     = intval($_SESSION["user_id"]);
$kode_mk     = trim($_POST["kode_mk"]);
$nama_mk     = trim($_POST["nama_mata_kuliah"]);
$semester    = intval($_POST["semester"]);
$id_prodi    = intval($_POST["id_program_studi"]);
$id_dosen    = !empty($_POST["id_dosen"]) ? intval($_POST["id_dosen"]) : null;
$hari        = trim($_POST["hari"]);
$jam_mulai   = !empty($_POST["jam_mulai"]) ? $_POST["jam_mulai"] : null;
$jam_selesai = !empty($_POST["jam_selesai"]) ? $_POST["jam_selesai"] : null;
$ruangan     = trim($_POST["ruangan"]);
$sks         = intval($_POST["sks"]);

$sql = "
    INSERT INTO mata_kuliah (
        kode_mk,
        nama_mata_kuliah,
        semester,
        id_program_studi,
        id_dosen,
        hari,
        jam_mulai,
        jam_selesai,
        ruangan,
        sks,
        created_at,
        created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssiiissssii",
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
    $id_user
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: list_matkul.php?status=sukses");
    exit;
}

die("Error: " . mysqli_error($db));
