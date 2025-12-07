<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Program Studi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f5f5;
    font-family: Poppins, sans-serif;
}

.title-box {
    background: linear-gradient(to right, #0A0A60, #B00032);
    padding: 25px;
    border-radius: 16px;
    color: white;
    text-align: center;
    margin: 0 auto 30px auto;
    max-width: 500px;
}

.box {
    background: white;
    border-radius: 16px;
    padding: 25px;
    border: 1px solid rgba(0,0,0,0.15);
    max-width: 500px;
    margin: 0 auto;
}

.btn-custom {
    background: #004D98;
    color: white;
    border-radius: 8px;
}
.btn-custom:hover {
    background: #003575;
}

.btn-red {
    background: #A50044;
    color: white;
    border-radius: 8px;
}
.btn-red:hover {
    background: #7a0033;
}
</style>
</head>
<body>

<?php
include("../config.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = null;

if ($id > 0) {
    $q = mysqli_query($db, "SELECT * FROM program_studi WHERE id = $id");
    $data = mysqli_fetch_assoc($q);
}
?>

<div class="container py-5">

    <div class="title-box">
        <h3 class="fw-bold">
            <?php echo $id ? 'Edit Program Studi' : 'Tambah Program Studi'; ?>
        </h3>
    </div>

    <div class="box">

        <form 
            method="post" 
            action="<?php echo $id ? 'proses_edit_program_studi.php' : 'proses_tambah_program_studi.php'; ?>"
        >

            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Prodi</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="nama_prodi"
                    value="<?php echo $data['nama_prodi'] ?? ''; ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Fakultas</label>
                <input 
                    type="text"
                    class="form-control" 
                    name="nama_fakultas"
                    value="<?php echo $data['nama_fakultas'] ?? ''; ?>"
                    required
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="simpan" class="btn btn-custom">Simpan</button>
                <a href="list_program_studi.php" class="btn btn-red">Kembali</a>
            </div>

        </form>

    </div>

</div>

</body>
</html>
