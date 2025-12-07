<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Form tidak valid");
}

$id_dosen       = isset($_POST['id_dosen']) ? intval($_POST['id_dosen']) : 0;
$id_mata_kuliah = isset($_POST['id_mata_kuliah']) ? intval($_POST['id_mata_kuliah']) : 0;
$semester       = isset($_POST['semester']) ? intval($_POST['semester']) : 0;
$hari           = isset($_POST['hari']) ? trim($_POST['hari']) : '';
$waktu_mulai    = isset($_POST['waktu_mulai']) ? trim($_POST['waktu_mulai']) : '';
$waktu_selesai  = isset($_POST['waktu_selesai']) ? trim($_POST['waktu_selesai']) : '';
$created_by     = intval($_SESSION['user_id']);

$sql = "
    INSERT INTO pengampu (
        id_dosen,
        id_mata_kuliah,
        semester,
        hari,
        waktu_mulai,
        waktu_selesai,
        created_by,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
";

$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {
    die("Error prepare: " . mysqli_error($db));
}

mysqli_stmt_bind_param(
    $stmt,
    "iiisssi",
    $id_dosen,
    $id_mata_kuliah,
    $semester,
    $hari,
    $waktu_mulai,
    $waktu_selesai,
    $created_by
);

if (!mysqli_stmt_execute($stmt)) {
    die("Error execute: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

header("Location: list_pengampu.php?status=sukses");
exit;