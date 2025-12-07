<?php
session_start();
include("../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "mahasiswa") {
    die("Akses ditolak");
}

$id_mhs = $_SESSION['user_id'];
$id_mk  = isset($_GET['id_mk']) ? intval($_GET['id_mk']) : 0;

if ($id_mk === 0) {
    die("Mata kuliah tidak valid.");
}

// Cek apakah sudah diambil sebelumnya
$cek = mysqli_query($db, "
    SELECT id FROM krs 
    WHERE id_mahasiswa=$id_mhs AND id_mata_kuliah=$id_mk
");

if (mysqli_num_rows($cek) > 0) {
    header("Location: krs_saya.php?status=duplikat");
    exit;
}

$q = mysqli_query($db, "
    INSERT INTO krs(id_mahasiswa,id_mata_kuliah,status_validasi,created_at)
    VALUES($id_mhs,$id_mk,'menunggu',NOW())
");

if ($q) {
    header("Location: krs_saya.php?status=sukses");
    exit;
}

header("Location: krs_saya.php?status=gagal");
exit;
