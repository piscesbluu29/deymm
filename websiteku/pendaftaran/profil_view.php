<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak");
}

$userId = intval($_SESSION['user_id']);

$q = mysqli_query($db, "
    SELECT
        w.nama,
        w.nim,
        w.prodi,
        w.alamat,
        w.foto,
        w.hp,
        w.email,
        w.jenis_kelamin,
        w.agama,
        w.sekolah_asal,
        d.nama_dosen AS dospem
    FROM tb_website w
    LEFT JOIN dosen d ON d.id = w.id_dospem
    WHERE w.user_id = $userId
");

$data = mysqli_fetch_assoc($q);

$foto = "../uploads/default.jpg";
if (!empty($data['foto'])) {
    $foto = "../uploads/" . $data['foto'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil Mahasiswa</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f5f7;
        margin: 0;
        padding: 25px;
    }

    .box {
        background: white;
        padding: 30px;
        border-radius: 14px;
        width: 700px;
        margin: auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        text-align: center;
    }

    .title {
        background: linear-gradient(to right, #A50044, #004D98);
        color: white;
        padding: 16px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 20px;
        font-weight: bold;
    }

    .foto {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        text-align: left;
        margin-top: 20px;
    }

    .item {
        background: #fafafa;
        padding: 14px;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .label {
        font-weight: bold;
    }

    .value {
        margin-top: 4px;
        font-size: 15px;
    }

    .btn {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 24px;
        background: #004D98;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
    }

    .btn-update {
        background: #0A0A60;
    }

    .btn-hapus-foto {
        padding: 8px 16px;
        background: #b40000;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }
</style>

</head>
<body>
<div class="box">

    <div class="title">Data Diri Anda</div>

    <img src="<?php echo $foto; ?>" class="foto">

    <?php if (!empty($data['foto'])) { ?>
        <form action="hapus_foto.php" method="post" style="margin-bottom:20px;">
            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($data['foto']); ?>">
            <button type="submit" class="btn-hapus-foto">
                Hapus Foto
            </button>
        </form>
    <?php } ?>

    <div class="grid">

        <div class="item">
            <div class="label">Nama</div>
            <div class="value"><?php echo htmlspecialchars($data['nama']); ?></div>
        </div>

        <div class="item">
            <div class="label">NIM</div>
            <div class="value"><?php echo htmlspecialchars($data['nim']); ?></div>
        </div>

        <div class="item">
            <div class="label">Prodi</div>
            <div class="value"><?php echo htmlspecialchars($data['prodi']); ?></div>
        </div>

        <div class="item">
            <div class="label">Dosen Pembimbing</div>
            <div class="value">
                <?php echo $data['dospem'] ? htmlspecialchars($data['dospem']) : "Belum ditentukan"; ?>
            </div>
        </div>

        <div class="item">
            <div class="label">Jenis Kelamin</div>
            <div class="value"><?php echo htmlspecialchars($data['jenis_kelamin']); ?></div>
        </div>

        <div class="item">
            <div class="label">Agama</div>
            <div class="value"><?php echo htmlspecialchars($data['agama']); ?></div>
        </div>

        <div class="item">
            <div class="label">Alamat</div>
            <div class="value"><?php echo htmlspecialchars($data['alamat']); ?></div>
        </div>

        <div class="item">
            <div class="label">No HP</div>
            <div class="value"><?php echo htmlspecialchars($data['hp']); ?></div>
        </div>

        <div class="item">
            <div class="label">Email</div>
            <div class="value"><?php echo htmlspecialchars($data['email']); ?></div>
        </div>

        <div class="item">
            <div class="label">Sekolah Asal</div>
            <div class="value"><?php echo htmlspecialchars($data['sekolah_asal']); ?></div>
        </div>

    </div>

    <a class="btn btn-update" href="profil_edit.php">Update Profil</a>
    <a class="btn" href="index.php">Kembali</a>
</div>

</body>
</html>