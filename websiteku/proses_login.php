<?php
session_start();
include("config.php");

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM tb_user WHERE email = ?";
    $st = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($st, "s", $email);
    mysqli_stmt_execute($st);
    $result = mysqli_stmt_get_result($st);

    // cek data user
    if ($result && mysqli_num_rows($result) == 1) {

        $u = mysqli_fetch_assoc($result);

        // cek password
        if (password_verify($password, $u['password'])) {

            // simpan session dasar
            $_SESSION['user_id'] = $u['id'];      
            $_SESSION['username'] = $u['username'];
            $_SESSION['role'] = $u['role'];

            // jika ADMIN
            if ($u['role'] == "admin") {
                header("Location: pendaftaran/admin/index.php");
                exit;
            }

            // jika MAHASISWA
            if ($u['role'] == "mahasiswa") {
                header("Location: pendaftaran/index.php");
                exit;
            }

            // jika DOSEN
            if ($u['role'] == "dosen") {

                // ambil id dosen dari tabel dosen
                $q = "SELECT id FROM dosen WHERE user_id = ?";
                $st2 = mysqli_prepare($db, $q);
                mysqli_stmt_bind_param($st2, "i", $u['id']);
                mysqli_stmt_execute($st2);
                $res = mysqli_stmt_get_result($st2);

                if ($res && mysqli_num_rows($res) == 1) {
                    $dosen = mysqli_fetch_assoc($res);

                    // simpan id_dosen ke session
                    $_SESSION['id_dosen'] = $dosen['id'];
                }

                header("Location: pendaftaran/dosen/index.php");
                exit;
            }
        }

        $_SESSION['error'] = "Password salah";
        header("Location: index.php");
        exit;
    }

    $_SESSION['error'] = "Email tidak ditemukan";
    header("Location: index.php");
    exit;
}
?>
