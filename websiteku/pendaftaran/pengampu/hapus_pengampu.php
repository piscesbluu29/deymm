<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$id = intval($_GET['id']);
$deleted_by = intval($_SESSION['user_id']);

$sql = "
    UPDATE pengampu
    SET deleted_at = NOW(),
        deleted_by = ?
    WHERE id = ?
";

$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {
    die("Error prepare: " . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt, "ii", $deleted_by, $id);
mysqli_stmt_execute($stmt);

header("Location: list_pengampu.php?status=hapus_sukses");
exit;
?>
