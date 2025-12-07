<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}
$id_user = intval($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Form tidak valid");
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("ID tidak diterima");
}

$id        = intval($_POST['id']);
$nim       = $_POST['nim'] ?? '';
$nama      = $_POST['nama'] ?? '';
$prodi     = $_POST['prodi'] ?? '';   // ambil PRODI
$alamat    = $_POST['alamat'] ?? '';
$jk        = $_POST['jenis_kelamin'] ?? '';
$agama     = $_POST['agama'] ?? '';
$sekolah   = $_POST['sekolah_asal'] ?? '';
$hp        = $_POST['hp'] ?? '';
$email     = $_POST['email'] ?? '';
$idDospem  = isset($_POST['id_dospem']) ? intval($_POST['id_dospem']) : 0;

$sql = "UPDATE tb_website
        SET nim=?, nama=?, prodi=?, alamat=?, jenis_kelamin=?, agama=?, sekolah_asal=?, 
            hp=?, email=?, id_dospem=?, updated_at=NOW(), updated_by=?
        WHERE id=?";

$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {
    die("Prepare gagal: " . mysqli_error($db));
}

$bind = mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssii",
    $nim,
    $nama,
    $prodi,     // masuk bind
    $alamat,
    $jk,
    $agama,
    $sekolah,
    $hp,
    $email,
    $idDospem,
    $id_user,
    $id
);

if (!$bind) {
    die("Bind gagal: " . mysqli_stmt_error($stmt));
}

$exec = mysqli_stmt_execute($stmt);

if (!$exec) {
    die("Execute gagal: " . mysqli_stmt_error($stmt));
}

header("Location: list_data.php?status=edit_sukses");
exit;
?>
