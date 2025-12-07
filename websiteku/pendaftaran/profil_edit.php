<?php

session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT nama, nim, prodi, alamat, hp, email, foto, id_dospem, jenis_kelamin, agama, sekolah_asal
        FROM tb_website
        WHERE user_id = ? LIMIT 1";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data tidak ditemukan.");
}

$nama      = htmlspecialchars($data['nama']);
$nim       = htmlspecialchars($data['nim']);
$prodi     = htmlspecialchars($data['prodi']);
$alamat    = htmlspecialchars($data['alamat']);
$hp        = htmlspecialchars($data['hp']);
$email     = htmlspecialchars($data['email']);
$jk        = htmlspecialchars($data['jenis_kelamin']);
$agama     = htmlspecialchars($data['agama']);
$sekolah   = htmlspecialchars($data['sekolah_asal']);
$id_dospem = intval($data['id_dospem']);

$dosenList = mysqli_query($db, "SELECT id, nama_dosen FROM dosen ORDER BY nama_dosen ASC");

$fotoFile = !empty($data['foto']) ? "../uploads/" . htmlspecialchars($data['foto']) : "../uploads/default.jpg";

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Profil Mahasiswa</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
            box-shadow: 0 10px 30px rgba(15,20,30,0.08);
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
        form { max-width:100%; margin: 0 auto; }
        .row { display:flex; gap:15px; margin-bottom:15px; }
        .col { flex:1; min-width:0; }
        label { display:block; font-weight:700; margin-bottom:7px; color:var(--text-color); font-size: 14px; }
        input[type="text"], input[type="email"], input[type="file"], textarea, select {
            width:100%;
            padding:12px;
            border-radius:8px;
            border:1px solid var(--border-color);
            box-sizing:border-box;
            font-size:15px;
        }
        input[type="file"] {
            padding: 9px;
            height: 43.6px;
        }
        textarea { resize:vertical; min-height:90px; }
        .actions { display:flex; gap:15px; margin-top: 30px; }
        .btn {
            flex:1;
            padding: 12px;
            border-radius:10px;
            text-align:center;
            text-decoration:none;
            color:#fff;
            font-weight:700;
            border:none;
            cursor:pointer;
            font-size: 16px;
        }
        .btn-save { background: var(--btn-blue); }
        .btn-cancel { background: var(--btn-red); }
        @media(max-width:640px){
            .row { flex-direction:column; }
            .actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="header">Edit Profil Mahasiswa</div>

    <img src="<?php echo $fotoFile; ?>" class="avatar">

    <form action="profil_update.php" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class="col">
                <label>Nama Lengkap</label>
                <input name="nama" type="text" value="<?php echo $nama; ?>" required>
            </div>
            <div class="col">
                <label>NIM</label>
                <input name="nim" type="text" value="<?php echo $nim; ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" <?php echo $jk == "Laki-laki" ? "selected" : ""; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo $jk == "Perempuan" ? "selected" : ""; ?>>Perempuan</option>
                </select>
            </div>
            <div class="col">
                <label>Agama</label>
                <select name="agama" required>
                    <option value="">Pilih Agama</option>
                    <option value="Islam" <?php echo $agama == "Islam" ? "selected" : ""; ?>>Islam</option>
                    <option value="Kristen" <?php echo $agama == "Kristen" ? "selected" : ""; ?>>Kristen</option>
                    <option value="Katolik" <?php echo $agama == "Katolik" ? "selected" : ""; ?>>Katolik</option>
                    <option value="Hindu" <?php echo $agama == "Hindu" ? "selected" : ""; ?>>Hindu</option>
                    <option value="Buddha" <?php echo $agama == "Buddha" ? "selected" : ""; ?>>Buddha</option>
                    <option value="Konghucu" <?php echo $agama == "Konghucu" ? "selected" : ""; ?>>Konghucu</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Prodi</label>
                <select name="prodi" class="form-control" required>
                <option value="">Pilih Prodi</option>

                <?php
                $listProdi = ['Informatika','Agribisnis','Teknik Mesin','Manajemen','Akuntansi'];

                foreach ($listProdi as $p) {
                    $sel = ($data['prodi'] == $p) ? 'selected' : '';
                    echo "<option value='$p' $sel>$p</option>";
                }
                ?>
            </select>
            </div>
            <div class="col">
                <label>Asal Sekolah</label>
                <input name="sekolah_asal" type="text" value="<?php echo $sekolah; ?>">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>No HP</label>
                <input name="hp" type="text" value="<?php echo $hp; ?>">
            </div>
            <div class="col">
                <label>Email</label>
                <input name="email" type="email" value="<?php echo $email; ?>">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Dosen Pembimbing</label>
                <select name="id_dospem" required>
                    <option value="">Pilih Dosen Pembimbing</option>
                    <?php while ($d = mysqli_fetch_assoc($dosenList)): ?>
                        <option value="<?php echo $d['id']; ?>" <?php echo $id_dospem == $d['id'] ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($d['nama_dosen']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col">
                <label>Ganti Foto Profil</label>
                <input name="foto" type="file" accept="image/*">
            </div>
        </div>

        <label>Alamat</label>
        <textarea name="alamat"><?php echo $alamat; ?></textarea>

        <div class="actions">
            <a href="profil_view.php" class="btn btn-cancel">Batal</a>
            <button type="submit" class="btn btn-save">Simpan Perubahan</button>
        </div>
    </form>

</div>
</body>
</html>