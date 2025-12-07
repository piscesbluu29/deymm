<?php

session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("Akses ditolak");
}

$userId    = intval($_SESSION['user_id']);

$nama      = $_POST['nama'] ?? '';
$alamat    = $_POST['alamat'] ?? '';
$jk        = $_POST['jenis_kelamin'] ?? '';
$agama     = $_POST['agama'] ?? '';
$sekolah   = $_POST['sekolah_asal'] ?? '';
$hp        = $_POST['hp'] ?? '';
$email     = $_POST['email'] ?? '';
$prodi     = $_POST['prodi'] ?? '';
$id_dospem = !empty($_POST['id_dospem']) ? intval($_POST['id_dospem']) : null;

$fotoBaru  = null;
$folder    = dirname(__DIR__) . "/uploads/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext     = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        $namaFile   = time() . "_" . uniqid() . "." . $ext;
        $lokasiBaru = $folder . $namaFile;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $lokasiBaru)) {
            $fotoBaru = $namaFile;

            $q   = mysqli_query($db, "SELECT foto FROM tb_website WHERE user_id=$userId");
            $old = mysqli_fetch_assoc($q)['foto'];

            if (!empty($old)) {
                $lamaPath = $folder . $old;
                if (file_exists($lamaPath)) {
                    unlink($lamaPath);
                }
            }
        }
    }
}

if ($fotoBaru) {
    $sql = "UPDATE tb_website
            SET nama=?, alamat=?, jenis_kelamin=?, agama=?, sekolah_asal=?, hp=?, email=?, prodi=?, id_dospem=?, foto=?
            WHERE user_id=?";

    $stmt = mysqli_prepare($db, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssisi",
        $nama,
        $alamat,
        $jk,
        $agama,
        $sekolah,
        $hp,
        $email,
        $prodi,
        $id_dospem,
        $fotoBaru,
        $userId
    );
} else {
    $sql = "UPDATE tb_website
            SET nama=?, alamat=?, jenis_kelamin=?, agama=?, sekolah_asal=?, hp=?, email=?, prodi=?, id_dospem=?
            WHERE user_id=?";

    $stmt = mysqli_prepare($db, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssii",
        $nama,
        $alamat,
        $jk,
        $agama,
        $sekolah,
        $hp,
        $email,
        $prodi,
        $id_dospem,
        $userId
    );
}

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: profil_view.php?update=ok");
exit;