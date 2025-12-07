<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id === 0) {
    header("Location:list_dosen.php");
    exit;
}

$deleted_by = intval($_SESSION['user_id']);

$sql = "UPDATE dosen 
        SET deleted_at=NOW(),
            deleted_by=$deleted_by
        WHERE id=$id";

if (mysqli_query($db, $sql)) {
    header("Location:list_dosen.php?status=hapus_sukses");
    exit;
}

die("Gagal hapus: " . mysqli_error($db));
?>
