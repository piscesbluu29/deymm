<?php

session_start();
include("../config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Metode request tidak valid.");
}

$nim       = $_POST['nim'] ?? '';
$nama      = $_POST['nama'] ?? '';
$prodi     = $_POST['prodi'] ?? '';       // PRODI WAJIB
$alamat    = $_POST['alamat'] ?? '';
$jk        = $_POST['jenis_kelamin'] ?? '';
$agama     = $_POST['agama'] ?? '';
$sekolah   = $_POST['sekolah_asal'] ?? '';
$hp        = $_POST['hp'] ?? '';
$email     = $_POST['email'] ?? '';
$idDospem  = isset($_POST['id_dospem']) ? intval($_POST['id_dospem']) : 0;

if (empty($nim) || empty($nama) || empty($prodi)) {
    header("Location: list_data.php?status=error_input_required");
    exit;
}

$password = password_hash($nim, PASSWORD_DEFAULT);

$status = "sukses";
$userBaru = null;

mysqli_begin_transaction($db);

try {

    // buat user login
    $sqlUser = "INSERT INTO tb_user (username, email, password, role)
                VALUES (?, ?, ?, 'mahasiswa')";
    $stUser = mysqli_prepare($db, $sqlUser);

    if (!$stUser) {
        throw new Exception("Prepare user error: " . mysqli_error($db));
    }

    mysqli_stmt_bind_param($stUser, "sss", $nama, $email, $password);

    if (!mysqli_stmt_execute($stUser)) {
        throw new Exception("Execute user error: " . mysqli_stmt_error($stUser));
    }

    $userBaru = mysqli_insert_id($db);
    mysqli_stmt_close($stUser);

    // input mahasiswa lengkap
    $sqlWebsite = "
        INSERT INTO tb_website
        (user_id, nim, nama, prodi, alamat, jenis_kelamin, agama, sekolah_asal, hp, email, id_dospem, created_at, created_by)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
    ";

    $stWebsite = mysqli_prepare($db, $sqlWebsite);

    if (!$stWebsite) {
        throw new Exception("Prepare website error: " . mysqli_error($db));
    }

    mysqli_stmt_bind_param(
        $stWebsite,
        "isssssssssii",
        $userBaru,
        $nim,
        $nama,
        $prodi,      // masuk ke database
        $alamat,
        $jk,
        $agama,
        $sekolah,
        $hp,
        $email,
        $idDospem,
        $userBaru
    );

    if (!mysqli_stmt_execute($stWebsite)) {
        throw new Exception("Execute website error: " . mysqli_stmt_error($stWebsite));
    }

    mysqli_stmt_close($stWebsite);

    mysqli_commit($db);

} catch (Exception $e) {
    mysqli_rollback($db);
    $status = "error_db";
    error_log("Error tambah mahasiswa: " . $e->getMessage());
}

header("Location: list_data.php?status={$status}");
exit;

?>
