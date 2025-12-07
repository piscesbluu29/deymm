<?php

include("../config.php");
session_start();

if (!isset($_POST['simpan'])) {
    header("Location:list_program_studi.php");
    exit;
}

$user = $_SESSION['user']['id'] ?? null;

$nama = mysqli_real_escape_string($db, $_POST['nama_prodi']);
$fak = mysqli_real_escape_string($db, $_POST['nama_fakultas']);

$q = mysqli_query(
    $db,
    "INSERT INTO program_studi (nama_prodi, nama_fakultas, created_at, created_by)
     VALUES ('$nama', '$fak', NOW(), '$user')"
);

if ($q) {
    header("Location:list_program_studi.php?status=sukses");
    exit;
}

die("Error: " . mysqli_error($db));
?>