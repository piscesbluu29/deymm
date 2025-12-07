<?php
session_start();
include("../../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../index.php");
    exit;
}

if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['role']) || !isset($_POST['password'])) {
    header("Location: form_user.php");
    exit;
}

$username = mysqli_real_escape_string($db, $_POST['username']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$role = mysqli_real_escape_string($db, $_POST['role']);
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO tb_user (username, email, role, password, created_at) VALUES (?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $role, $password_hash);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: list_user.php");
exit;
?>
