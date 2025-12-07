<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$id_user = intval($_SESSION['user_id']);
$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("ID tidak valid");
}

$sql = "UPDATE tb_website
        SET deleted_at=NOW(), deleted_by=?
        WHERE id=?";

$stmt = mysqli_prepare($db, $sql);

if ($stmt === false) {
    die("Prepare gagal: " . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt, "ii", $id_user, $id);
mysqli_stmt_execute($stmt);

header("Location: list_data.php?status=hapus_sukses");
exit;
?>