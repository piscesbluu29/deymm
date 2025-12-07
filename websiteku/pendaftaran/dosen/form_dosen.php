<div class="container">
    <div class="header-box">
        <h3>Tambah Dosen</h3>
    </div>

    <form method="post" action="proses_tambah_dosen.php" class="form-box">

        <label>NIDN</label>
        <input type="text" name="nidn" required>

        <label>Nama Dosen</label>
        <input type="text" name="nama_dosen" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <div class="btn-group">
            <button type="submit" name="simpan">Simpan</button>
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
        opacity: .9;
    }
</style>
