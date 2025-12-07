<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Form tidak valid");
}

$id_dosen       = intval($_POST['id_dosen']);
$id_mahasiswa   = intval($_POST['id_mahasiswa']);
$id_mata_kuliah = intval($_POST['id_mata_kuliah']);
$semester       = intval($_POST['semester']);
$hari           = $_POST['hari'];
$waktu_mulai    = $_POST['waktu_mulai'];
$waktu_selesai  = $_POST['waktu_selesai'];
$created_by     = intval($_SESSION['user_id']);

$sql = "
    INSERT INTO perkuliahan (
        id_dosen,
        id_mata_kuliah,
        id_mahasiswa,
        semester,
        hari,
        waktu_mulai,
        waktu_selesai,
        created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($db, $sql);

if ($stmt === false) {
    die("Error prepare: " . mysqli_error($db));
}

mysqli_stmt_bind_param(
    $stmt,
    "iiiisssi",
    $id_dosen,
    $id_mata_kuliah,
    $id_mahasiswa,
    $semester,
    $hari,
    $waktu_mulai,
    $waktu_selesai,
    $created_by
);

$execute = mysqli_stmt_execute($stmt);

if ($execute === false) {
    die("Error execute: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

header("Location: list_perkuliahan.php?status=sukses");
exit;
?>
