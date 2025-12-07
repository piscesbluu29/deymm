<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "localhost";
$user = "root";
$password = "";
$nama_database = "db_website";

$db = mysqli_connect($server, $user, $password, $nama_database);

if(!$db){
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
if (!$db) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

?>
