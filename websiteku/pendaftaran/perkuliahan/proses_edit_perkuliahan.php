<?php
include("../config.php");
session_start();

$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : die('User belum login');

if (isset($_POST['update'])) {
    $id              = intval($_POST['id']);
    $id_dosen        = intval($_POST['id_dosen']);
    $id_mata_kuliah  = intval($_POST['id_mata_kuliah']);
    $id_mahasiswa    = intval($_POST['id_mahasiswa']);

    $query = mysqli_query($db, "
        UPDATE perkuliahan 
        SET id_dosen = $id_dosen, 
            id_mata_kuliah = $id_mata_kuliah, 
            id_mahasiswa = $id_mahasiswa,
            updated_at = NOW(),
            updated_by = '$id_user'
        WHERE id = $id
    ");

    if ($query) {
        header("Location:list_perkuliahan.php?status=edit_sukses");
    } else {
        die("Error: " . mysqli_error($db));
    }
}
?>
