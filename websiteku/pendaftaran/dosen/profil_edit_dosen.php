<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Mengambil data dosen berdasarkan ID pengguna dari tabel tb_user
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
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data dosen tidak ditemukan");
}

// Sanitasi data untuk ditampilkan di HTML
$nidn  = htmlspecialchars($data["nidn"]);
$nama  = htmlspecialchars($data["nama_dosen"]);
$email = htmlspecialchars($data["email"]);

// Penentuan path foto
// Perlu disesuaikan pathnya: ../../uploads/ karena file ini ada di folder 'dosen',
// sedangkan 'uploads' ada di direktori root (level kakek).
$fotoFile = !empty($data["foto"])
    ? "../../uploads/" . htmlspecialchars($data["foto"])
    : "../../uploads/default.jpg";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Profil Dosen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    :root {
        --gradient-start: #A50044;
        --gradient-end: #004D98;
        --btn-blue: #0A0A60;
        --btn-red: #A50044;
        --border-color: #d6d9de;
        --text-color: #222;
    }
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }
    .card {
        max-width: 700px;
        margin: 24px auto;
        background: #fff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(15, 20, 30, 0.08);
    }
    .header {
        background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        color: #fff;
        padding: 15px 20px;
        border-radius: 10px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 25px;
    }
    .avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--gradient-end);
        display: block;
        margin: 10px auto 25px;
    }
    form { max-width: 100%; margin: 0 auto; }
    .row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        align-items: flex-start;
    }
    .col { flex: 1; min-width: 0; }
    label {
        display: block;
        font-weight: 700;
        margin-bottom: 7px;
        color: var(--text-color);
        font-size: 14px;
        text-align: left;
    }
    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 15px;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }
    input:focus {
        border-color: var(--gradient-end);
        outline: none;
    }
    .file { 
        padding: 10px 0; 
        font-size: 14px; 
    }
    .actions {
        display: flex; 
        gap: 15px; 
        margin-top: 30px;
    }
    .btn {
        flex: 1;
        padding: 12px;
        border-radius: 10px;
        text-align: center;
        color: #fff;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
    }
    .btn-save { 
        background: var(--btn-blue); 
    }
    .btn-cancel { 
        background: var(--btn-red); 
    }
    
    @media(max-width: 640px) {
        .row { flex-direction: column; gap: 0; }
        .col { margin-bottom: 15px; }
        .actions { flex-direction: column; }
        .btn { width: 100%; }
    }
</style>

</head>
<body>
<div class="card">
    <div class="header">Edit Profil Dosen</div>
    <img src="<?php echo $fotoFile; ?>" class="avatar">
    
    <form action="proses_update_dosen.php" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class="col">
                <label for="nama_dosen">Nama Lengkap</label>
                <input id="nama_dosen" name="nama_dosen" type="text" value="<?php echo $nama; ?>" required>
            </div>
            <div class="col">
                <label for="nidn">NIDN</label>
                <input id="nidn" name="nidn" type="text" value="<?php echo $nidn; ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="email">Email Aktif</label>
                <input id="email" name="email" type="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="col">
                <label for="foto">Ganti Foto Profil</label>
                <input id="foto" name="foto" class="file" type="file" accept="image/*">
            </div>
        </div>

        <div class="actions">
            <a href="profil_view_dosen.php" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-save">Simpan Perubahan</button>
        </div>
    </form>
</div>
</body>
</html>