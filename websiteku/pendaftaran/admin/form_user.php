<?php  
include("../../config.php");
?>

<h3>Tambah User</h3>

<form action="proses_tambah_user.php" method="POST">

    <label for="username">Username</label><br>
    <input type="text" name="username" id="username" required><br><br>
    
    <label for="email">Email</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password</label><br>
    <input type="text" name="password" id="password" required><br><br>

    <label for="role">Role</label><br>
    <select name="role" id="role" required>
        <option value="admin">Admin</option>
        <option value="dosen">Dosen</option>
        <option value="mahasiswa">Mahasiswa</option>
    </select><br><br>
    
    <button type="submit" name="simpan">Simpan</button>

</form>

<a href="list_user.php">Kembali</a>
