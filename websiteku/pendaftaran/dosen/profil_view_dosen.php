<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$userId = $_SESSION['user_id'];

// Query menggunakan JOIN untuk memastikan data dosen sesuai dengan user_id dari tb_user
$sql = "
    SELECT d.nidn, d.nama_dosen, d.email, d.foto
    FROM dosen d
    JOIN tb_user u ON u.email = d.email
    WHERE u.id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$data = mysqli_fetch_assoc($res);

// Tutup statement
mysqli_stmt_close($stmt);

if (!$data) {
    // Inisialisasi data dengan nilai kosong jika tidak ditemukan
    $data = [
        "nidn" => "",
        "nama_dosen" => "",
        "email" => "",
        "foto" => ""
    ];
}

// Penentuan path foto
$foto = "../../uploads/default.jpg";
if (!empty($data["foto"])) {
$foto = "../../uploads/" . $data["foto"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Dosen</title>
    <style>
        body { 
            font-family: Arial; 
            background: #f4f5f7; 
            padding: 25px; 
        }
        .box { 
            width: 480px; 
            margin: auto; 
            background: white; 
            padding: 25px; 
            border-radius: 14px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15); 
            text-align: center; 
        }
        .title { 
            background: linear-gradient(to right, #0A0A60, #A50044); 
            color: white; 
            padding: 16px; 
            border-radius: 10px; 
            margin-bottom: 20px; 
            font-size: 20px; 
        }
        .foto { 
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            margin-bottom: 20px; 
        }
        .label { 
            font-weight: bold; 
            margin-top: 12px; 
        }
        .value { 
            margin-top: 2px; 
        }
        .btn { 
            display: block; 
            width: 100%; 
            padding: 12px; 
            background: #004D98; 
            color: white; 
            text-decoration: none; 
            margin-top: 20px; 
            border-radius: 8px; 
            font-weight: bold; 
        }
        .btn-update { 
            background: #0A0A60; 
        }
    </style>
</head>
<body>
<div class="box">
    <div class="title">Profil Dosen</div>
    
    <img src="<?php echo $foto; ?>" class="foto">
    
    <div class="label">Nama</div>
    <div class="value"><?php echo htmlspecialchars($data['nama_dosen']); ?></div>
    
    <div class="label">NIDN</div>
    <div class="value"><?php echo htmlspecialchars($data['nidn']); ?></div>
    
    <div class="label">Email</div>
    <div class="value"><?php echo htmlspecialchars($data['email']); ?></div>
    
    <a href="profil_edit_dosen.php" class="btn btn-update">Edit Profil</a>
    <a href="../dosen/index.php" class="btn">Kembali</a>
</div>
</body>
</html>