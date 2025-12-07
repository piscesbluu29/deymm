<?php
session_start();
include("../config.php");

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$dosen = mysqli_query($db, "SELECT id, nama_dosen FROM dosen WHERE deleted_at IS NULL ORDER BY nama_dosen ASC");
$matkul = mysqli_query($db, "SELECT id, nama_mata_kuliah FROM mata_kuliah WHERE deleted_at IS NULL ORDER BY nama_mata_kuliah ASC");
?>

<style>
    body {
        font-family: Arial;
        margin: 0;
        padding: 25px;
        background: #f4f5f7;
    }

    .title-box {
        background: linear-gradient(to right, #A50044, #004D98);
        padding: 18px;
        border-radius: 10px;
        color: white;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: auto;
    }

    label {
        font-weight: bold;
    }

    select, input[type="time"] {
        width: 100%;
        padding: 8px;
        margin-top: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-submit {
        background: #004D98;
        color: white;
        padding: 10px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 15px;
    }

    .btn-back {
        background: #444;
        color: white;
        padding: 10px 14px;
        border-radius: 6px;
        text-decoration: none;
        margin-left: 10px;
    }
</style>

<div class="title-box">Tambah Pengampu</div>

<div class="card">

<form method="post" action="proses_tambah_pengampu.php">

    <div>
        <label for="id_dosen">Dosen</label>
        <select name="id_dosen" id="id_dosen" required>
            <option value="">Pilih Dosen</option>
            <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
                <option value="<?php echo $d['id']; ?>">
                    <?php echo htmlspecialchars($d['nama_dosen']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <br>

    <div>
        <label for="id_mata_kuliah">Mata Kuliah</label>
        <select name="id_mata_kuliah" id="id_mata_kuliah" required>
            <option value="">Pilih Mata Kuliah</option>
            <?php while ($m = mysqli_fetch_assoc($matkul)) { ?>
                <option value="<?php echo $m['id']; ?>">
                    <?php echo htmlspecialchars($m['nama_mata_kuliah']); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <br>

    <div>
        <label for="semester">Semester</label>
        <select name="semester" id="semester" required>
            <option value="">Pilih Semester</option>
            <?php for ($i=1; $i<=8; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </div>

    <br>

    <div>
        <label for="hari">Hari</label>
        <select name="hari" id="hari" required>
            <option value="">Pilih Hari</option>
            <option>Senin</option>
            <option>Selasa</option>
            <option>Rabu</option>
            <option>Kamis</option>
            <option>Jumat</option>
            <option>Sabtu</option>
        </select>
    </div>

    <br>

    <div>
        <label for="waktu_mulai">Waktu Mulai</label>
        <input type="time" name="waktu_mulai" id="waktu_mulai" required>
    </div>

    <br>

    <div>
        <label for="waktu_selesai">Waktu Selesai</label>
        <input type="time" name="waktu_selesai" id="waktu_selesai" required>
    </div>

    <button type="submit" class="btn-submit" name="simpan">Simpan</button>
    <a href="list_pengampu.php" class="btn-back">Kembali</a>

</form>

</div>
