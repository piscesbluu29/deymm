<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fotoLama = $_POST['foto_lama'];
    $path = "../uploads/" . $fotoLama;

    if (file_exists($path)) {
        unlink($path);
    }

    mysqli_query($db, "
        UPDATE tb_website 
        SET foto = NULL 
        WHERE user_id = $userId
    ");

    header("Location: profil_view.php");
    exit;
}
?>
