<?php
session_start();
include "../config/database.php";

// Proteksi Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

// PROSES HAPUS ANGGOTA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // 1. Hapus dulu di user_login (karena ada relasi ref_id)
    mysqli_query($conn, "DELETE FROM user_login WHERE ref_id = '$id'");
    
    // 2. Hapus di tabel anggota
    $hapus = mysqli_query($conn, "DELETE FROM anggota WHERE id_anggota = '$id'");
    
    if ($hapus) {
        echo "<script>alert('Data anggota dan akun login berhasil dihapus!'); window.location='admin_anggota.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus! Anggota mungkin masih memiliki riwayat peminjaman.'); window.location='admin_anggota.php';</script>";
    }
}

// Ambil Data Anggota
$query = mysqli_query($conn, "SELECT * FROM anggota ORDER BY tgl_bergabung DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; }
        .main-content { margin-left: 250px; padding: 20px; }
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
        <a class="nav-link" href="admin_buku.php"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="admin_peminjaman.php"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link active" href="admin_anggota.php"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="../logout.php"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Manajemen Anggota</h2>
            <div class="text-muted">Total: <?= mysqli_num_rows($query); ?> Anggota</div>
        </div>

        <div class="card shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama Lengkap</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Bergabung</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $row['id_anggota']; ?></span></td>
                            <td>
                                <div class="fw-bold"><?= $row['nama']; ?></div>
                                <small class="text-muted"><?= $row['email']; ?></small>
                            </td>
                            <td><?= $row['no_telp']; ?></td>
                            <td class="small text-truncate" style="max-width: 150px;"><?= $row['alamat']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl_bergabung'])); ?></td>
                          
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if (mysqli_num_rows($query) == 0) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada anggota yang terdaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>