<?php
session_start();
require_once __DIR__.'/../controllers/MahasiswaController.php';
$controller = new MahasiswaController();

// Hapus data
if(isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if($controller->delete($id)) {
        $message = "Data berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data!";
    }
}

// Tambah data
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['action']) && $_POST['action']=='create') {
    $nama  = $_POST['nama'];
    $nim   = $_POST['nim'];
    $prodi = $_POST['prodi'];

    if($controller->createData($nama, $nim, $prodi)) {
        $message = "Data berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan data!";
    }
}

// Search
$keyword = $_GET['keyword'] ?? '';
$data = $controller->read($keyword);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="../css/sb-admin-2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2>Data Mahasiswa</h2>

<?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<!-- Form Tambah Data -->
<?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
<div class="card mb-3 p-3">
    <h4>Tambah Mahasiswa</h4>
    <form method="POST" class="form-inline mb-3">
        <input type="hidden" name="action" value="create">
        <input type="text" name="nama" class="form-control mr-2 mb-2" placeholder="Nama" required>
        <input type="text" name="nim" class="form-control mr-2 mb-2" placeholder="NIM" required>
        <input type="text" name="prodi" class="form-control mr-2 mb-2" placeholder="Prodi" required>
        <button type="submit" class="btn btn-primary mb-2">Tambah</button>
    </form>
</div>
<?php endif; ?>

<!-- Form Search -->
<form method="GET" class="form-inline mb-3">
    <input type="text" name="keyword" class="form-control mr-2 mb-2" placeholder="Cari mahasiswa..." value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit" class="btn btn-info mb-2">Cari</button>
</form>

<!-- Table Daftar Mahasiswa -->
<table class="table table-bordered table-striped">
<thead class="thead-dark">
<tr>
    <th>ID</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($d = $data->fetch_assoc()): ?>
<tr>
    <td><?= $d['id'] ?></td>
    <td><?= htmlspecialchars($d['nim']); ?></td>
    <td><?= htmlspecialchars($d['nama']); ?></td>
    <td><?= htmlspecialchars($d['prodi']); ?></td>
    <td>
        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='admin'): ?>
            <a href="edit.php?id=<?= $d['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="?action=delete&id=<?= $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script src="../js/sb-admin-2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
