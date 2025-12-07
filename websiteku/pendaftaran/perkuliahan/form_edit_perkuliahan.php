<?php
include("../config.php");

$id   = intval($_GET['id']);
$res  = mysqli_query($db, "SELECT * FROM perkuliahan WHERE id=$id");
$row  = mysqli_fetch_assoc($res);

$dosen     = mysqli_query($db, "SELECT * FROM dosen");
$matkul    = mysqli_query($db, "SELECT * FROM mata_kuliah");
$mahasiswa = mysqli_query($db, "SELECT * FROM tb_website");
?>

<h3>Edit Perkuliahan</h3>

<form method="post" action="proses_edit_perkuliahan.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    Dosen:
    <select name="id_dosen" required>
        <?php while ($d = mysqli_fetch_assoc($dosen)) : ?>
            <option value="<?php echo $d['id']; ?>" <?php if ($d['id'] == $row['id_dosen']) echo 'selected'; ?>>
                <?php echo $d['nama_dosen']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    Mata Kuliah:
    <select name="id_mata_kuliah" required>
        <?php while ($m = mysqli_fetch_assoc($matkul)) : ?>
            <option value="<?php echo $m['id']; ?>" <?php if ($m['id'] == $row['id_mata_kuliah']) echo 'selected'; ?>>
                <?php echo $m['nama_mata_kuliah']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    Mahasiswa:
    <select name="id_mahasiswa" required>
        <?php while ($w = mysqli_fetch_assoc($mahasiswa)) : ?>
            <option value="<?php echo $w['id']; ?>" <?php if ($w['id'] == $row['id_mahasiswa']) echo 'selected'; ?>>
                <?php echo $w['nama']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <button type="submit" name="update">Update</button>
    <a href="list_perkuliahan.php">Kembali</a>
</form>
