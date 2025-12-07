<?php
session_start();
include("../../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../index.php");
    exit;
}

$sql = "SELECT id, username, email, role FROM tb_user ORDER BY id ASC";
$result = mysqli_query($db, $sql);
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
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background: #004D98;
        color: white;
        padding: 10px;
        text-align: left;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background: #f0f4ff;
    }

    .btn {
        padding: 7px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        background: #004D98;
        font-size: 14px;
    }

    .btn.red {
        background: #A50044;
    }

    .back {
        margin-top: 20px;
        display: inline-block;
        padding: 8px 14px;
        background: #444;
        color: white;
        border-radius: 6px;
        text-decoration: none;
    }
</style>

<div class="title-box">Kelola User</div>

<div class="card">

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td>
                <a class="btn" href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a class="btn red" 
                   href="hapus_user.php?id=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Yakin?')">
                   Hapus
                </a>
            </td>
        </tr>
<?php 
}
?>

    </tbody>
</table>

</div>

<a class="back" href="index.php">Kembali</a>
