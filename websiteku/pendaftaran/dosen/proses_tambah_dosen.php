<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

if (isset($_POST['simpan'])) {

    $nidn        = trim($_POST['nidn']);
    $nama_dosen  = trim($_POST['nama_dosen']);
    $email       = trim($_POST['email']);

    if ($nidn == "" || $nama_dosen == "" || $email == "") {
        die("Semua field wajib diisi.");
    }

    // Password otomatis = NIDN
    $password = password_hash($nidn, PASSWORD_DEFAULT);

    // Cek email duplikat
    $cek = mysqli_query($db, "SELECT id FROM tb_user WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: form_dosen.php?status=duplikat");
        exit;
    }

    mysqli_begin_transaction($db);

    try {

        // Insert user baru
        $sql_user = "
            INSERT INTO tb_user (username, email, password, role)
            VALUES (?, ?, ?, 'dosen')
        ";

        $stUser = mysqli_prepare($db, $sql_user);
        mysqli_stmt_bind_param($stUser, "sss", $email, $email, $password);
        mysqli_stmt_execute($stUser);

        $userBaru = mysqli_insert_id($db);
        mysqli_stmt_close($stUser);

        // Insert data dosen
        $sql_dosen = "
            INSERT INTO dosen (nidn, nama_dosen, email, user_id, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ";

        $stDosen = mysqli_prepare($db, $sql_dosen);
        mysqli_stmt_bind_param(
            $stDosen,
            "sssii",
            $nidn,
            $nama_dosen,
            $email,
            $userBaru,
            $_SESSION['user_id']
        );

        mysqli_stmt_execute($stDosen);
        mysqli_stmt_close($stDosen);

        mysqli_commit($db);

        header("Location: list_dosen.php?status=sukses");
        exit;

    } catch (Exception $e) {

        mysqli_rollback($db);
        die("Gagal menyimpan dosen: " . $e->getMessage());
    }
}
?>
