<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$userId = $_SESSION['user_id'];

$nidn  = $_POST['nidn'];
$nama  = $_POST['nama_dosen'];
$email = $_POST['email'];

$folder = dirname(__DIR__) . "/uploads/";
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$fotoBaru = null;

// --- Proses Upload Foto Baru ---
if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0) {
    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    
    // Validasi ekstensi
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {

        $namaFile = time() . "_" . uniqid() . "." . $ext;
        $tujuan = $folder . $namaFile;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $tujuan)) {

            // Ambil nama foto lama
            $q = mysqli_query($db, "
                SELECT d.foto 
                FROM dosen d 
                JOIN tb_user u ON u.email = d.email 
                WHERE u.id = $userId
            ");

            $old = mysqli_fetch_assoc($q)['foto'];

            // Hapus foto lama jika ada
            if (!empty($old) && file_exists($folder . $old)) {
                unlink($folder . $old);
            }

            $fotoBaru = $namaFile;
        }
    }
}
// --- Akhir Proses Upload Foto Baru ---

// Persiapan query UPDATE menggunakan JOIN dan Prepared Statement
if ($fotoBaru) {
    // Dengan foto baru
    $sql = "
        UPDATE dosen d
        JOIN tb_user u ON u.email = d.email
        SET d.nidn=?, d.nama_dosen=?, d.email=?, d.foto=?, d.updated_at=NOW(), d.updated_by=?
        WHERE u.id=?
    ";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssssii", $nidn, $nama, $email, $fotoBaru, $userId, $userId);

} else {
    // Tanpa foto baru
    $sql = "
        UPDATE dosen d
        JOIN tb_user u ON u.email = d.email
        SET d.nidn=?, d.nama_dosen=?, d.email=?, d.updated_at=NOW(), d.updated_by=?
        WHERE u.id=?
    ";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $nidn, $nama, $email, $userId, $userId);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: profil_view_dosen.php");
exit;