<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

if (isset($_POST['update'])) {

    $id          = intval($_POST['id']);
    $nidn        = mysqli_real_escape_string($db, $_POST['nidn']);
    $nama        = mysqli_real_escape_string($db, $_POST['nama_dosen']);
    $email       = mysqli_real_escape_string($db, $_POST['email']);
    $updated_by  = intval($_SESSION['user_id']);

    $sql = "UPDATE dosen 
            SET nidn='$nidn',
                nama_dosen='$nama',
                email='$email',
                updated_at=NOW(),
                updated_by=$updated_by
            WHERE id=$id";

    if (mysqli_query($db, $sql)) {
        header("Location:list_dosen.php?status=edit_sukses");
        exit;
    }

    die("Error: " . mysqli_error($db));
}

die("Form tidak dikirim dengan benar");
?>
