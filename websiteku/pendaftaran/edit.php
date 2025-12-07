<?php

include 'koneksi.php';

// --- Validasi dan Sanitasi Awal ---
if (!isset($_GET['id'], $_GET['table'])) {
    http_response_code(400);
    die("ID atau tabel tidak ada");
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$table = $_GET['table'];
$allowed_tables = ['dosen', 'mata_kuliah', 'perkuliahan'];

// Periksa ID dan Tabel
if ($id === false || $id <= 0) {
    http_response_code(400);
    die("ID tidak valid");
}
if (!in_array($table, $allowed_tables)) {
    http_response_code(400);
    die("Tabel tidak valid");
}

// --- Ambil Data Lama (Prepared Statement) ---
$sql_select = "SELECT * FROM {$table} WHERE id = ?";
$stmt_select = mysqli_prepare($db, $sql_select);

if (!$stmt_select) {
    http_response_code(500);
    die("Query gagal: " . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);

if (!$row) {
    http_response_code(404);
    die("Data tidak ditemukan");
}

$status = "";

// --- Proses Update (Prepared Statement) ---
if (isset($_POST['update'])) {
    $update_success = false;
    $bind_types = "";
    $bind_params = [];
    $sql_update = "";

    if ($table == "dosen") {
        $nidn = $_POST['nidn'] ?? '';
        $nama = $_POST['nama_dosen'] ?? '';
        $sql_update = "UPDATE dosen SET nidn=?, nama_dosen=? WHERE id=?";
        $bind_types = "ssi";
        $bind_params = [$nidn, $nama, $id];
        
    } elseif ($table == "mata_kuliah") {
        $kode = $_POST['kode_mk'] ?? '';
        $nama = $_POST['nama_mk'] ?? '';
        $sql_update = "UPDATE mata_kuliah SET kode_mk=?, nama_mk=? WHERE id=?";
        $bind_types = "ssi";
        $bind_params = [$kode, $nama, $id];
        
    } elseif ($table == "perkuliahan") {
        // Amankan dan pastikan input adalah integer
        $id_dosen = filter_var($_POST['id_dosen'] ?? 0, FILTER_VALIDATE_INT);
        $id_mk = filter_var($_POST['id_mk'] ?? 0, FILTER_VALIDATE_INT);

        if ($id_dosen === false || $id_mk === false) {
             $status = "Input ID Dosen atau ID MK tidak valid.";
             goto display_form;
        }

        $sql_update = "UPDATE perkuliahan SET id_dosen=?, id_mk=? WHERE id=?";
        $bind_types = "iii";
        $bind_params = [$id_dosen, $id_mk, $id];
    }

    if ($sql_update) {
        $stmt_update = mysqli_prepare($db, $sql_update);
        
        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, $bind_types, ...$bind_params);
            
            if (mysqli_stmt_execute($stmt_update)) {
                $update_success = true;
            } else {
                $status = "Update gagal: " . mysqli_stmt_error($stmt_update);
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $status = "Update gagal (Prepare): " . mysqli_error($db);
        }
    }
    
    if ($update_success) {
        header("Location: index_{$table}.php?status=update_sukses");
        exit;
    }
}

display_form:
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo ucfirst(str_replace('_', ' ', $table)); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

    <div class="card mx-auto shadow-lg" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Edit Data <?php echo ucfirst(str_replace('_', ' ', $table)); ?></h3>

            <?php if ($status): ?>
                <div class="alert alert-danger"><?php echo $status; ?></div>
            <?php endif; ?>

            <form method="post">
                <?php if ($table == "dosen"): ?>
                    <div class="mb-3">
                        <label for="nidn" class="form-label">NIDN</label>
                        <input type="text" class="form-control" id="nidn" name="nidn" 
                               value="<?php echo htmlspecialchars($row['nidn'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_dosen" class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" 
                               value="<?php echo htmlspecialchars($row['nama_dosen'] ?? ''); ?>" required>
                    </div>
                
                <?php elseif ($table == "mata_kuliah"): ?>
                    <div class="mb-3">
                        <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" 
                               value="<?php echo htmlspecialchars($row['kode_mk'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" 
                               value="<?php echo htmlspecialchars($row['nama_mk'] ?? ''); ?>" required>
                    </div>
                    
                <?php elseif ($table == "perkuliahan"): ?>
                    <div class="mb-3">
                        <label for="id_dosen" class="form-label">ID Dosen</label>
                        <input type="number" class="form-control" id="id_dosen" name="id_dosen" 
                               value="<?php echo htmlspecialchars($row['id_dosen'] ?? ''); ?>" required>
                        <div class="form-text">Masukkan ID Dosen yang baru.</div>
                    </div>
                    <div class="mb-3">
                        <label for="id_mk" class="form-label">ID Mata Kuliah</label>
                        <input type="number" class="form-control" id="id_mk" name="id_mk" 
                               value="<?php echo htmlspecialchars($row['id_mk'] ?? ''); ?>" required>
                        <div class="form-text">Masukkan ID Mata Kuliah yang baru.</div>
                    </div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index_<?php echo $table; ?>.php" class="btn btn-secondary">Batal</a>
                    <input type="submit" name="update" value="Update Data" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>