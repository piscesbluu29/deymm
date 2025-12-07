<?php
session_start();
include("../config.php");

// 1. Pengecekan Akses dan Sesi
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    die("Akses ditolak");
}

// 2. Pengecekan ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("ID tidak ditemukan atau tidak valid");
}

$id = mysqli_real_escape_string($db, $_GET["id"]); 
$sql = "DELETE FROM tb_user WHERE id='$id'";
$run_query = mysqli_query($db, $sql);

if ($run_query) {
    // 5. Redirect Sukses
    header("Location: list_user.php?status=hapus_sukses");
} else {
    // 5. Penanganan Error
    die("Gagal menghapus data: " . mysqli_error($db));
}

exit();
?>