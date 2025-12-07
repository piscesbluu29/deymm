<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../../index.php");
    exit;
}

include("../../config.php");

if (!isset($_POST['id']) || !isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['role'])) {
    header("Location: list_user.php");
    exit;
}

$id = intval($_POST['id']);
$username = mysqli_real_escape_string($db, $_POST['username']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$role = mysqli_real_escape_string($db, $_POST['role']);
$pass = $_POST['password'];

// Update dengan password baru jika diisi
if (!empty($pass)) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "UPDATE tb_user SET username=?, email=?, role=?, password=? WHERE id=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $role, $hash, $id);
} else {
    $sql = "UPDATE tb_user SET username=?, email=?, role=? WHERE id=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $role, $id);
}

if (!mysqli_stmt_execute($stmt)) {
    die("Update gagal: " . mysqli_error($db));
}

mysqli_stmt_close($stmt);

header("Location: list_user.php");
exit;
?>
