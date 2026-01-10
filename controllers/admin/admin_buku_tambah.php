<?php
session_start();
include "../../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../login.php");
    exit;
}

/* =========================
   1. AUTO GENERATE ID BUKU
========================= */
$q = mysqli_query($conn, "SELECT id_koleksi FROM koleksi ORDER BY id_koleksi DESC LIMIT 1");
$data = mysqli_fetch_assoc($q);

if ($data) {
    $lastId = (int) substr($data['id_koleksi'], 2); 
    $newId  = "BK" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
} else {
    $newId = "BK001";
}

/* =========================
   2. PROSES SIMPAN DATA
========================= */
if (isset($_POST['simpan'])) {
    $id_koleksi  = $newId;
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis     = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit    = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun       = $_POST['tahun_terbit'];
    $id_kategori = $_POST['id_kategori'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if ($gambar != "") {
        $nama_file_baru = date('dmYHis') . "_" . $gambar;
        // 3. Jalur Upload: naik 2 tingkat
        move_uploaded_file($tmp, "../../uploads/koleksi/" . $nama_file_baru);
        $file_db = $nama_file_baru;
    } else {
        $file_db = "default.jpg";
    }

    $query = "INSERT INTO koleksi (id_koleksi, judul, penulis, penerbit, tahun_terbit, id_kategori, gambar)
              VALUES ('$id_koleksi', '$judul', '$penulis', '$penerbit', '$tahun', '$id_kategori', '$file_db')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Buku berhasil ditambahkan!');
                window.location='../../admin/admin_buku.php';
              </script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku | Libook Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold">LIBOOK</h4>
        <small>Administrator</small>
    </div>
    <hr>
    <nav class="nav flex-column">
        <a class="nav-link" href="../../admin/admin_dashboard.php"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="nav-link active" href="../../admin/admin_buku.php"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="../../admin/admin_peminjaman.php"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="../../admin/admin_anggota.php"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="../../logout.php"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm p-4">
                    <h3 class="fw-bold text-primary mb-4">➕ Tambah Koleksi Buku</h3>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">ID Buku</label>
                                <input type="text" class="form-control bg-light" value="<?= $newId ?>" readonly>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Judul Buku</label>
                                <input type="text" name="judul" class="form-control" placeholder="Contoh: Belajar PHP Dasar" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Penulis</label>
                                <input type="text" name="penulis" class="form-control" placeholder="Nama penulis">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
                                    while($k = mysqli_fetch_assoc($kat)) {
                                        echo "<option value='{$k['id_kategori']}'>{$k['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" value="<?= date('Y') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Cover Buku (Gambar)</label>
                            <input type="file" name="gambar" class="form-control">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB.</small>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="../../admin/admin_buku.php" class="btn btn-light px-4 text-secondary">Batal</a>
                            <button type="submit" name="simpan" class="btn btn-primary px-5 shadow-sm">Simpan Buku</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>