<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Anggota') {
    header("Location: login.php");
    exit;
}

$id_anggota = $_SESSION['ref_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pinjam | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table thead th { 
            background-color: #f8f9fa; 
            border-bottom: 2px solid #eee;
            color: #6c757d; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 1px;
            padding: 1.2rem 1rem;
        }
        .table tbody td { padding: 1.2rem 1rem; border-bottom: 1px solid #f1f1f1; }
        .status-badge { 
            border-radius: 50px; 
            padding: 6px 16px; 
            font-size: 0.75rem; 
            font-weight: 700; 
            display: inline-flex;
            align-items: center;
        }
        .book-icon {
            width: 40px;
            height: 40px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 15px;
        }
        .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="index.php">
            <img src="assets/img/logo.png" width="40" height="40" class="me-2" alt="Logo">Libook
        </a>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
                        <i class="fa fa-user-circle me-1"></i> Hi, <?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?> 👋
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="index.php"><i class="fa fa-book me-2"></i> Katalog</a></li>
                        <li><a class="dropdown-item" href="wishlist.php"><i class="fa fa-star me-2 text-warning"></i> Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted small">Katalog</a></li>
                    <li class="breadcrumb-item active small" aria-current="page">Riwayat Peminjaman</li>
                </ol>
            </nav>

            <div class="bg-white p-4 rounded-4 shadow-sm border-start border-primary border-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                        <i class="fa-solid fa-clock-rotate-left fa-xl"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">Riwayat Peminjaman</h3>
                        <p class="text-muted mb-0 small">Pantau daftar buku yang sedang atau pernah kamu baca.</p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Katalog
                </a>
            </div>

            <div class="alert alert-primary border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-info fa-lg me-3"></i>
                <div class="small">
                    <strong>Informasi:</strong> Batas peminjaman adalah <strong>7 hari</strong>. Mohon kembalikan tepat waktu untuk menghindari denda.
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID Transaksi</th>
                                    <th>Informasi Buku</th>
                                    <th>Waktu Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($conn, "
                                    SELECT p.*, k.judul, k.penulis
                                    FROM peminjaman p
                                    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                                    JOIN koleksi k ON d.id_koleksi = k.id_koleksi
                                    WHERE p.id_anggota = '$id_anggota'
                                    ORDER BY p.tgl_pinjam DESC
                                ");

                                if(mysqli_num_rows($q) > 0):
                                    while ($r = mysqli_fetch_assoc($q)):
                                        $is_dipinjam = ($r['status'] == 'Dipinjam');
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark"><?= $r['id_peminjaman']; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="book-icon">
                                                <i class="fa-solid fa-book"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= $r['judul']; ?></div>
                                                <small class="text-muted"><?= $r['penulis']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-dark"><i class="fa-regular fa-calendar-check me-2 text-primary"></i><?= date('d M Y', strtotime($r['tgl_pinjam'])); ?></div>
                                            <div class="text-muted mt-1"><i class="fa-regular fa-calendar-xmark me-2 text-danger"></i>Sampai <?= date('d M Y', strtotime($r['tgl_kembali'])); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge bg-<?= $is_dipinjam ? 'warning' : 'success'; ?> bg-opacity-10 text-<?= $is_dipinjam ? 'warning' : 'success'; ?> border border-<?= $is_dipinjam ? 'warning' : 'success'; ?> border-opacity-25">
                                            <i class="fa-solid <?= $is_dipinjam ? 'fa-clock' : 'fa-circle-check'; ?> me-2"></i>
                                            <?= $r['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <h5 class="fw-bold">Belum Ada Riwayat</h5>
                                        <p class="text-muted small px-5">Kamu belum meminjam buku apa pun. Silakan jelajahi katalog kami untuk menemukan bacaan menarik.</p>
                                        <a href="index.php" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Pinjam Buku</a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="text-center py-5">
                <p class="text-muted small mb-0">© <?= date('Y'); ?> Libook Digital Library. Semua data tercatat secara sistematis.</p>
            </footer>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>