<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../../index.php");
    exit;
}

include("../../config.php");

if (!isset($_GET['id'])) {
    header("Location: list_user.php");
    exit;
}

$id = intval($_GET['id']);

$sql_select = "SELECT id, username, email, role FROM tb_user WHERE id = $id";
$q = mysqli_query($db, $sql_select);

if (!$q) {
    die("Query gagal. " . mysqli_error($db));
}

$user = mysqli_fetch_assoc($q);

if (!$user) {
    die("User tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0A0A60, #B00032);
            min-height: 100vh;
            font-family: Poppins, sans-serif;
        }
        .center-box {
            max-width: 480px;
            margin: 70px auto;
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }
        h3 {
            text-align: center;
            font-weight: 700;
            color: #0A0A60;
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .btn-main {
            background: #0A0A60;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
        }
        .btn-main:hover {
            background: #040449;
        }
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 18px;
            padding: 10px;
            border-radius: 8px;
            background: #B00032;
            color: white;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #7c0024;
        }
    </style>
</head>
<body>

<div class="center-box">

    <h3>Edit User</h3>

    <form action="proses_edit_user.php" method="POST">
        
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

        <div class="mb-3">
            <label>Username</label>
            <input 
                type="text" 
                name="username" 
                class="form-control"
                value="<?php echo htmlspecialchars($user['username']); ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input 
                type="email" 
                name="email" 
                class="form-control"
                value="<?php echo htmlspecialchars($user['email']); ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?php if ($user['role']=='admin') echo 'selected'; ?>>Admin</option>
                <option value="mahasiswa" <?php if ($user['role']=='mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                <option value="dosen" <?php if ($user['role']=='dosen') echo 'selected'; ?>>Dosen</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Password baru (opsional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-main">Update</button>

    </form>

    <a class="btn-back" href="list_user.php">Kembali</a>

</div>

</body>
</html>
