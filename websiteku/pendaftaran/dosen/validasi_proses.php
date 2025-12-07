<?php
session_start();
include("../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "dosen") {
    header("Location: ../index.php");
    exit;
}

$aksi   = $_GET['aksi'] ?? '';
$id_krs = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_mhs = isset($_GET['mhs']) ? intval($_GET['mhs']) : 0;

if ($aksi === "approve" || $aksi === "tolak") {

    if ($id_krs <= 0) {
        header("Location: validasi_krs.php?msg=id_invalid");
        exit;
    }

    $status = ($aksi === "approve") ? "disetujui" : "ditolak";

    $sql = "UPDATE krs SET status_validasi = ? WHERE id = ?";
    $st  = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($st, "si", $status, $id_krs);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);

    header("Location: validasi_krs.php?msg=ok_single");
    exit;
}

if ($aksi === "approve_all") {

    if ($id_mhs <= 0) {
        header("Location: validasi_krs.php?msg=mhs_invalid");
        exit;
    }

    $sql = "UPDATE krs SET status_validasi='disetujui' WHERE id_mahasiswa=?";
    $st  = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($st, "i", $id_mhs);
    mysqli_stmt_execute($st);
    mysqli_stmt_close($st);

    header("Location: validasi_krs.php?msg=ok_all");
    exit;
}
header("Location: validasi_krs.php?msg=unknown_action");
exit;
