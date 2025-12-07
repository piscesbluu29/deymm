<?php
include("../config.php");
session_start();

$id = intval($_GET['id']);
$user = $_SESSION['user']['id'];

$q = mysqli_query(
    $db,
    "UPDATE program_studi
     SET deleted_at = NOW(),
         deleted_by = '$user'
     WHERE id = $id"
);

if ($q) {
    header("Location:list_program_studi.php?status=hapus_sukses");
    exit;
}

die("Error: " . mysqli_error($db));
?>