<?php
session_start();
include("../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "mahasiswa") {
    die("Akses ditolak");
}

$id_mhs = $_SESSION['user_id'];
$id_krs = intval($_GET['id']);

// Pastikan data punya mahasiswa ini
$cek = mysqli_query($db, "SELECT id FROM krs WHERE id=$id_krs AND id_mahasiswa=$id_mhs");

if (mysqli_num_rows($cek) == 0) {
    header("Location: krs_saya.php?status=hapus_gagal");
    exit;
}

mysqli_query($db, "DELETE FROM krs WHERE id=$id_krs");

header("Location: krs_saya.php?status=hapus_ok");
exit;
