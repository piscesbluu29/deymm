<?php
session_start();
include("../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION["user_id"];

$sql = "SELECT email FROM tb_user WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

$email = $user ? htmlspecialchars($user["email"]) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Ubah Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --gradient-start: #A50044;
            --gradient-end: #004D98;
            --btn-blue: #0A0A60;
            --btn-red: #A50044;
            --border-color: #d6d9de;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .card {
            max-width: 600px;
            margin: 24px auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
        }
        label {
            font-weight: 700;
            margin-top: 15px;
            display: block;
            font-size: 14px;
        }
        input[type=password] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-top: 4px;
            font-size: 15px;
            box-sizing: border-box;
        }
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-size: 16px;
        }
        .btn-save { background: var(--btn-blue); }
        .btn-cancel { background: var(--btn-red); }
    </style>
</head>
<body>

<div class="card">
    <div class="header">Ubah Password</div>

    <form action="proses_password.php" method="post">
        
        <label for="old_pass">Password Lama</label>
        <input type="password" id="old_pass" name="old_pass" required>

        <label for="new_pass">Password Baru</label>
        <input type="password" id="new_pass" name="new_pass" required>

        <label for="new_pass2">Ulangi Password Baru</label>
        <input type="password" id="new_pass2" name="new_pass2" required>

        <div class="actions">
            <a href="index.php" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-save">Simpan</button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const params = new URLSearchParams(window.location.search);

if (params.has("status")) {
    let status = params.get("status");

    if (status === "ok") {
        Swal.fire({
            icon: "success",
            title: "Password berhasil diubah",
            timer: 1800,
            showConfirmButton: false
        }).then(() => {
            window.location = "index.php";
        });
    } else {
        Swal.fire({
            icon: "error",
            title: params.get("msg") || "Gagal mengubah password"
        });
    }
}
</script>


</body>
</html>
