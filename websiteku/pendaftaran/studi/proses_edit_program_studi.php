<?php

include("../config.php");
session_start();

if (!isset($_POST['simpan'])) {
    header("Location:list_program_studi.php");
    exit;
}

$id = intval($_POST['id']);
$user = $_SESSION['user']['id'];

$nama = mysqli_real_escape_string($db, $_POST['nama_prodi']);
$fak = mysqli_real_escape_string($db, $_POST['nama_fakultas']);

$q = mysqli_query(
    $db,
    "UPDATE program_studi
     SET nama_prodi = '$nama',
         nama_fakultas = '$fak',
         updated_at = NOW(),
         updated_by = '$user'
     WHERE id = $id"
);

if ($q) {
    header("Location:list_program_studi.php?status=edit_sukses");
    exit;
}

die("Error: " . mysqli_error($db));
?>