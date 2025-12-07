<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id'])) {
    die("User belum login");
}

$id_user = $_SESSION['user_id'];

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) die("ID tidak valid");

$query = mysqli_query($db, "SELECT * FROM dosen WHERE id=$id AND deleted_at IS NULL");
$row = mysqli_fetch_assoc($query);
if (!$row) die("Data tidak ditemukan");

if (isset($_POST['update'])) {

    $nidn        = mysqli_real_escape_string($db, $_POST['nidn']);
    $nama_dosen  = mysqli_real_escape_string($db, $_POST['nama_dosen']);
    $email       = mysqli_real_escape_string($db, $_POST['email']);

    $update = mysqli_query(
        $db,
        "UPDATE dosen 
         SET nidn='$nidn',
             nama_dosen='$nama_dosen',
             email='$email',
             updated_at=NOW(),
             updated_by=$id_user
         WHERE id=$id"
    );

    if ($update) {
        header("Location:list_dosen.php?status=edit_sukses");
        exit;
    } else {
        die("Gagal update: " . mysqli_error($db));
    }
}
?>

<div class="container">
    <div class="header-box">
        <h3>Edit Dosen</h3>
    </div>

    <form method="post" class="form-box">

        <label>NIDN</label>
        <input 
            type="text" 
            name="nidn" 
            value="<?php echo htmlspecialchars($row['nidn']); ?>" 
            required
        >

        <label>Nama Dosen</label>
        <input 
            type="text" 
            name="nama_dosen" 
            value="<?php echo htmlspecialchars($row['nama_dosen']); ?>" 
            required
        >

        <label>Email</label>
        <input 
            type="email" 
            name="email" 
            value="<?php echo htmlspecialchars($row['email']); ?>" 
            required
        >

        <div class="btn-group">
            <button type="submit" name="update">Update</button>
            <a href="list_dosen.php" class="btn-kembali">Kembali</a>
        </div>

    </form>
</div>

<style>
    .container {
        width: 400px;
        margin: 40px auto;
        text-align: left;
        font-family: Arial;
    }

    .header-box {
        background: linear-gradient(to right, #A50044, #004D98);
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        margin-bottom: 20px;
    }

    .header-box h3 {
        color: white;
        margin: 0;
    }

    .form-box {
        background: white;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 8px;
        border: 1px solid #aaa;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    button {
        background: #004D98;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-kembali {
        background: #A50044;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 4px;
    }

    button:hover, .btn-kembali:hover {
        opacity: 0.9;
    }
</style>
