<?php
session_start();
include("../config.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "dosen") {
    header("Location: ../index.php");
    exit;
}

$user_id  = $_SESSION['user_id'];
$id_dosen = $_SESSION['id_dosen'] ?? 0;

if ($id_dosen <= 0) {
    $q = mysqli_prepare($db, "SELECT id FROM dosen WHERE user_id=? LIMIT 1");
    mysqli_stmt_bind_param($q, "i", $user_id);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    if ($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['id_dosen'] = $row['id'];
        $id_dosen = $row['id'];
    }
    mysqli_stmt_close($q);
}

if ($id_dosen <= 0) {
    echo "<div style='padding:20px;font-family:sans-serif;'>
            <h3>Akun dosen belum terhubung dengan tabel dosen.</h3>
            <a href='index.php'>Kembali</a>
          </div>";
    exit;
}

$listMhsArr = [];
$qMhs = mysqli_prepare($db, "SELECT id, user_id, nama, nim FROM tb_website WHERE id_dospem=? ORDER BY nama ASC");
mysqli_stmt_bind_param($qMhs, "i", $id_dosen);
mysqli_stmt_execute($qMhs);
$resMhs = mysqli_stmt_get_result($qMhs);
while ($row = mysqli_fetch_assoc($resMhs)) {
    $listMhsArr[] = $row;
}
mysqli_stmt_close($qMhs);

$filter = isset($_GET['mhs']) ? intval($_GET['mhs']) : 0;

$sql = "
    SELECT
        k.id AS id_krs,
        k.status_validasi,
        mk.kode_mk,
        mk.nama_mata_kuliah,
        mk.hari,
        mk.sks,
        mk.ruangan,
        mk.jam_mulai,
        mk.jam_selesai,
        tw.user_id AS id_mahasiswa,
        tw.nama AS nama_mahasiswa,
        tw.nim AS nim_mahasiswa
    FROM krs k
    JOIN mata_kuliah mk ON mk.id = k.id_mata_kuliah
    JOIN tb_website tw ON tw.user_id = k.id_mahasiswa
    WHERE tw.id_dospem = ?
";

$types = "i";
$params = [$id_dosen];

if ($filter > 0) {
    $sql .= " AND tw.user_id = ? ";
    $types .= "i";
    $params[] = $filter;
}

$sql .= " ORDER BY tw.nama ASC, k.id DESC";

$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$resList = mysqli_stmt_get_result($stmt);

$group = [];
while ($r = mysqli_fetch_assoc($resList)) {
    $group[$r['id_mahasiswa']][] = $r;
}
mysqli_stmt_close($stmt);

function status_badge_html($raw) {
    $s = strtolower(trim((string)$raw));
    $approved = ['disetujui','approved','setuju'];
    $rejected = ['ditolak','rejected','tolak'];
    if (in_array($s,$approved)) return '<span class="badge bg-success">Disetujui</span>';
    if (in_array($s,$rejected)) return '<span class="badge bg-danger">Ditolak</span>';
    return '<span class="badge bg-secondary">Belum</span>';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Validasi KRS Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background:#f4f6f9;
        font-family: Poppins, sans-serif;
    }

    .title-box {
        background:linear-gradient(135deg,#0A0A60,#B00032);
        padding:24px;
        border-radius:16px;
        color:#fff;
        text-align:center;
        margin-bottom:22px;
    }

    .filter-box {
        background:#fff;
        padding:18px;
        border-radius:12px;
        border:1px solid rgba(0,0,0,0.08);
        margin-bottom:20px;
    }

    table thead th {
        background:#004D98;
        color:#fff;
        text-align:center;
    }

    .card-custom {
        border-radius:12px;
        border:1px solid rgba(0,0,0,0.08);
        overflow:hidden;
    }

    .card-header {
        background:#0A0A60;
        color:#fff;
        font-weight:700;
        padding:12px 16px;
    }

    .btn-approve {
        background:#0A772F;
        color:white;
    }

    .btn-reject {
        background:#B00032;
        color:white;
    }

    .btn-approve-all {
        background:#0A0A60;
        color:white;
    }
</style>

</head>
<body class="p-4">

<div class="container">

    <div class="title-box">
        <h3 class="mb-1">Validasi KRS Mahasiswa</h3>
        <p class="mb-0">Kelola persetujuan mata kuliah mahasiswa bimbingan</p>
    </div>

    <div class="filter-box">
        <form method="GET" class="d-flex gap-2">
            <select name="mhs" class="form-control" style="max-width:320px;">
                <option value="0">Semua Mahasiswa</option>
                <?php foreach ($listMhsArr as $m): ?>
                    <option value="<?= $m['user_id']; ?>" <?= ($filter == $m['user_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nama']); ?> (<?= htmlspecialchars($m['nim']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <?php if (empty($group)): ?>
        <div class="alert alert-warning text-center">Belum ada KRS untuk divalidasi</div>
    <?php endif; ?>

    <?php foreach ($group as $uid => $items): ?>
        <div class="card mb-4 shadow-sm card-custom">

            <div class="card-header">
                <?= htmlspecialchars($items[0]['nama_mahasiswa']); ?>
                (<?= htmlspecialchars($items[0]['nim_mahasiswa']); ?>)
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-2">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th class="text-start">Mata Kuliah</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Ruangan</th>
                                <th>SKS</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($items as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['kode_mk']); ?></td>
                                <td class="text-start"><?= htmlspecialchars($r['nama_mata_kuliah']); ?></td>
                                <td><?= htmlspecialchars($r['hari']); ?></td>
                                <td>
                                    <?= $r['jam_mulai'] ? substr($r['jam_mulai'],0,5) : '-'; ?>
                                    -
                                    <?= $r['jam_selesai'] ? substr($r['jam_selesai'],0,5) : '-'; ?>
                                </td>
                                <td><?= htmlspecialchars($r['ruangan']); ?></td>
                                <td><?= htmlspecialchars($r['sks']); ?></td>
                                <td><?= status_badge_html($r['status_validasi']); ?></td>

                                <td>
                                    <a href="validasi_proses.php?id=<?= $r['id_krs']; ?>&aksi=approve"
                                       class="btn btn-sm btn-approve">Setujui</a>

                                    <a href="validasi_proses.php?id=<?= $r['id_krs']; ?>&aksi=tolak"
                                       class="btn btn-sm btn-reject">Tolak</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

                <div class="text-end">
                    <a href="validasi_proses.php?mhs=<?= $uid; ?>&aksi=approve_all"
                       class="btn btn-approve-all btn-sm">
                        Setujui Semua Matkul
                    </a>
                </div>
                <div class="text-start mt-3">
    <a href="index.php" class="btn btn-danger" style="background:#B00032;color:white;">
        Kembali
    </a>
</div>


            </div>
        </div>
    <?php endforeach; ?>

</div>


</body>
</html>
