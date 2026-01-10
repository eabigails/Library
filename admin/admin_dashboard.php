<?php
session_start();
include "../config/database.php";

// Proteksi: Kalau bukan Admin, tendang balik ke login
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

// 1. Hitung Total Buku
$res_buku = mysqli_query($conn, "SELECT COUNT(*) as total FROM koleksi");
$total_buku = mysqli_fetch_assoc($res_buku)['total'];

// 2. Hitung Total Anggota
$res_anggota = mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota");
$total_anggota = mysqli_fetch_assoc($res_anggota)['total'];

// 3. Hitung Buku yang Sedang Dipinjam
$res_pinjam = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dipinjam'");
$total_pinjam = mysqli_fetch_assoc($res_pinjam)['total'];

$query_terbaru = "SELECT p.id_peminjaman, a.nama, k.judul, p.status 
                  FROM peminjaman p
                  JOIN anggota a ON p.id_anggota = a.id_anggota
                  JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
                  JOIN koleksi k ON dp.id_koleksi = k.id_koleksi
                  ORDER BY p.id_peminjaman DESC 
                  LIMIT 5";
$res_terbaru = mysqli_query($conn, $query_terbaru);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; transition: all 0.3s; }
        .main-content { margin-left: 250px; padding: 20px; }
        .card-stat { border: none; border-radius: 15px; transition: transform 0.3s; color: white; }
        .card-stat:hover { transform: translateY(-5px); }
        .nav-link { color: #bdc3c7; padding: 15px 20px; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold">LIBOOK</h4>
        <small class="text-white">Administrator</small>
    </div>
    <hr>
    <nav class="nav flex-column">
        <a class="nav-link active" href="admin_dashboard.php"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="nav-link" href="admin_buku.php"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="admin_peminjaman.php"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="admin_anggota.php"><i class="fa fa-users me-2"></i> Data Anggota</a>
       <a class="nav-link text-danger mt-5" href="../logout.php">
    <i class="fa fa-sign-out-alt me-2"></i> Log out
</a>
    </nav>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Selamat Datang, <?= $_SESSION['username']; ?>! 👋</h2>
            <span class="text-muted"><?= date('l, d F Y'); ?></span>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-stat bg-primary p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Koleksi</h6>
                            <h2 class="fw-bold mb-0"><?= $total_buku; ?></h2>
                        </div>
                        <i class="fa fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-success p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Anggota Aktif</h6>
                            <h2 class="fw-bold mb-0"><?= $total_anggota; ?></h2>
                        </div>
                        <i class="fa fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat bg-warning p-4 shadow-sm text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Sedang Dipinjam</h6>
                            <h2 class="fw-bold mb-0"><?= $total_pinjam; ?></h2>
                        </div>
                        <i class="fa fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h5 class="fw-bold mb-3">Aktivitas Peminjaman Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Peminjam</th>
                                    <th>Buku</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($res_terbaru) > 0) : ?>
                                    <?php while ($row = mysqli_fetch_assoc($res_terbaru)) : ?>
                                        <tr>
                                            <td>#<?= $row['id_peminjaman']; ?></td>
                                            <td><span class="fw-semibold"><?= $row['nama']; ?></span></td>
                                            <td><?= $row['judul']; ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'Dipinjam') : ?>
                                                    <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i> Dipinjam</span>
                                                <?php else : ?>
                                                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> Kembali</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas peminjaman.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>