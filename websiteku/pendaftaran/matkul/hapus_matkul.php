<?php
session_start();
include("../config.php");

if (!isset($_GET['id'])) {
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("User belum login atau bukan admin");
}

$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_user = intval($_SESSION['user_id']);

if ($id === 0) {
    header("Location: list_matkul.php?status=id_tidak_valid");
    exit;
}

$stmt = mysqli_prepare($db, "UPDATE mata_kuliah SET deleted_at = NOW(), deleted_by = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id_user, $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: list_matkul.php?status=hapus_sukses");
    exit;
}

header("Location: list_matkul.php?status=hapus_gagal");
exit;
