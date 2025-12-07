<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "localhost";
$user = "root";
$password = "";
$database = "db_website";

$db = mysqli_connect($server, $user, $password, $database);

if(!$db){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
