<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Mata Kuliah</title>
</head>
<body>
  <h3>Tambah Mata Kuliah</h3>
  <form action="proses_tambah_matkul.php" method="POST">
    <p>Kode MK: <input type="text" name="kode_mk" required></p>
    <p>Nama Mata Kuliah: <input type="text" name="nama_mata_kuliah" required></p>
    <p><input type="submit" value="Simpan"></p>
  </form>
  <a href="list_matkul.php">Kembali</a>
</body>
</html>
