<?php

include("../config.php");

$id = intval($_GET['id']);
$mk = mysqli_query($db, "SELECT * FROM mata_kuliah WHERE id = $id");
$data = mysqli_fetch_assoc($mk);

if (!$data) {
    die("Data mata kuliah tidak ditemukan.");
}

$prodi = mysqli_query($db, "SELECT id, nama_prodi FROM program_studi WHERE deleted_at IS NULL ORDER BY nama_prodi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mata Kuliah</title>

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
            max-width: 600px;
            margin: auto;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
        }

        input[type=text],
        input[type=number],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .btn {
            padding: 8px 14px;
            background: #004D98;
            color: white;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            background: #444;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>

</head>
<body>

<div class="title-box">Edit Mata Kuliah</div>

<div class="card">

    <form method="post" action="proses_edit_matkul.php">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <label>Kode MK</label>
        <input type="text" name="kode_mk" value="<?php echo $data['kode_mk']; ?>" required>

        <label>Nama MK</label>
        <input type="text" name="nama_mata_kuliah" value="<?php echo $data['nama_mata_kuliah']; ?>" required>

        <label>Semester</label>
        <input type="number" name="semester" value="<?php echo $data['semester']; ?>" required>

        <label>Program Studi</label>
        <select name="id_program_studi" required>
            <option value="">Pilih Program Studi</option>
            <?php while ($p = mysqli_fetch_assoc($prodi)) : ?>
                <option value="<?php echo $p['id']; ?>"
                    <?php echo ($p['id'] == $data['id_program_studi']) ? 'selected' : ''; ?>>
                    <?php echo $p['nama_prodi']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" name="update" class="btn">Update</button>
    </form>

    <a class="back" href="list_matkul.php">Kembali</a>

</div>

</body>
</html>
