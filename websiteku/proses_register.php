<?php

session_start();
include "config.php";

if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $password = $_POST['password'];
    $role = "admin";

    $sql_cek = "SELECT email FROM tb_user WHERE email='$email'";
    $cek = mysqli_query($db, $sql_cek);

    if (mysqli_num_rows($cek) > 0) {
        echo "Email sudah dipakai";
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert = "
        INSERT INTO tb_user (
            username, email, role, created_at, deleted_at, password
        )
        VALUES (
            '$username', '$email', '$role', NOW(), NULL, '$hash'
        )
    ";

    $ok = mysqli_query($db, $sql_insert);

    if ($ok) {
        header("Location: index.php");
        exit;
    }

    echo "Gagal mendaftar";
}

?>