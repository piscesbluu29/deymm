<?php
include("../config.php");

if (isset($_GET['buat'])) {
    $aksi = $_GET['buat'];

    if ($aksi == "dosen") {
        $q = mysqli_query($db, "SELECT id, nidn, email FROM dosen WHERE deleted_at IS NULL");
        $role = "dosen";

        while ($d = mysqli_fetch_assoc($q)) {
            $email = $d['email'];
            $ref = $d['id'];
            $pass = $d['nidn'];

            $stmt_check = mysqli_prepare($db, "SELECT id FROM tb_user WHERE email=? AND role=?");
            mysqli_stmt_bind_param($stmt_check, "ss", $email, $role);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) == 0) {
                $hash = password_hash($pass, PASSWORD_DEFAULT);

                $stmt_insert = mysqli_prepare($db, "
                    INSERT INTO tb_user (email, password, role, ref_id, created_at, deleted_at)
                    VALUES (?, ?, ?, ?, NOW(), NULL)
                ");
                mysqli_stmt_bind_param($stmt_insert, "sssi", $email, $hash, $role, $ref);
                mysqli_stmt_execute($stmt_insert);
            }

            mysqli_stmt_close($stmt_check);
        }

        header("Location: list_user.php");
        exit;
    }

    if ($aksi == "mahasiswa") {
        $q = mysqli_query($db, "SELECT id, nim, email FROM tb_website WHERE deleted_at IS NULL");
        $role = "mahasiswa";

        while ($m = mysqli_fetch_assoc($q)) {
            $email = $m['email'];
            $ref = $m['id'];
            $pass = $m['nim'];
            $username = $m['nim']; // gunakan NIM sebagai username

            $stmt_check = mysqli_prepare($db, "SELECT id FROM tb_user WHERE email=? AND role=?");
            mysqli_stmt_bind_param($stmt_check, "ss", $email, $role);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) == 0) {
                $hash = password_hash($pass, PASSWORD_DEFAULT);

                $stmt_insert = mysqli_prepare($db, "
                    INSERT INTO tb_user (username, email, password, role, ref_id, created_at, deleted_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NULL)
                ");
                mysqli_stmt_bind_param($stmt_insert, "sssii", $username, $email, $hash, $role, $ref);
                mysqli_stmt_execute($stmt_insert);
            }

            mysqli_stmt_close($stmt_check);
        }

        header("Location: list_user.php");
        exit;
    }
}

echo "Perintah tidak valid";
?>
