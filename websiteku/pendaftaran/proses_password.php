<?php
session_start();
include("../config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION["user_id"];

$old = $_POST["old_pass"] ?? '';
$new = $_POST["new_pass"] ?? '';
$new2 = $_POST["new_pass2"] ?? '';

$sql = "SELECT password FROM tb_user WHERE id=? LIMIT 1";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);

if (!$user) {
    header("Location: ubah_password.php?status=fail&msg=Data tidak ditemukan");
    exit;
}

$lama = $user["password"];

if (!password_verify($old, $lama)) {
    header("Location: ubah_password.php?status=fail&msg=Password lama salah");
    exit;
}

if ($new !== $new2) {
    header("Location: ubah_password.php?status=fail&msg=Konfirmasi tidak cocok");
    exit;
}

if (password_verify($new, $lama)) {
    header("Location: ubah_password.php?status=fail&msg=Password baru sama dengan lama");
    exit;
}

$newHash = password_hash($new, PASSWORD_DEFAULT);

$sql2 = "UPDATE tb_user SET password=? WHERE id=?";
$stmt2 = mysqli_prepare($db, $sql2);
mysqli_stmt_bind_param($stmt2, "si", $newHash, $userId);

if (mysqli_stmt_execute($stmt2)) {
    header("Location: ubah_password.php?status=ok");
    exit;
}

header("Location: ubah_password.php?status=fail&msg=Gagal update password");
exit;
?>
