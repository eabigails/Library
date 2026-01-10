<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Query disesuaikan dengan ERD terakhir (koleksi & kategori)
$query = "SELECT k.*, kt.nama_kategori 
          FROM koleksi k 
          LEFT JOIN kategori kt ON k.id_kategori = kt.id_kategori 
          ORDER BY k.id_koleksi DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
        .card { border: none; border-radius: 15px; }
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
        <a class="nav-link" href="admin_dashboard.php"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="nav-link active" href="admin_buku.php"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="admin_peminjaman.php"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="admin_anggota.php"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="../logout.php"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📚 Kelola Koleksi Buku</h2>
        <a href="../controllers/admin/admin_buku_tambah.php" class="btn btn-primary">
    <i class="fa fa-plus me-2"></i>Tambah Buku
</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Cover</th>
                        <th>ID</th>
                        <th>Judul & Penulis</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td class="ps-4">
                            <?php 
                                
                                $path_gambar = "../uploads/koleksi/" . $row['gambar']; 
                                if (!empty($row['gambar']) && file_exists($path_gambar)) {
                                    $tampil_gambar = $path_gambar;
                                } else {
                                    $tampil_gambar = "../assets/img/default_buku.jpg"; 
                                }
                            ?>
                            <img src="<?= $tampil_gambar ?>" style="width: 50px; height: 70px; object-fit: cover;" class="rounded shadow-sm">
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $row['id_koleksi'] ?></span></td>
                        <td>
                            <div class="fw-bold"><?= $row['judul'] ?></div>
                            <small class="text-muted"><?= $row['penulis'] ?></small>
                        </td>
                        <td><span class="badge bg-info-subtle text-info"><?= $row['nama_kategori'] ?? 'Umum' ?></span></td>
                        <td><?= $row['tahun_terbit'] ?></td>
                        <td class="text-center">
                            <a href="../controllers/admin/admin_buku_hapus.php?id=<?= $row['id_koleksi'] ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Yakin ingin menghapus buku ini?')">
                               <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>