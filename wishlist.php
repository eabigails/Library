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
    <title>Wishlist Saya | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
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
            background: #fffbeb;
            color: #d97706;
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
                        <li><a class="dropdown-item" href="riwayat.php"><i class="fa fa-file-text me-2 text-primary"></i> Riwayat Pinjam</a></li>
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
                    <li class="breadcrumb-item active small" aria-current="page">Wishlist Saya</li>
                </ol>
            </nav>

            <div class="bg-white p-4 rounded-4 shadow-sm border-start border-warning border-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3 text-warning">
                        <i class="fa-solid fa-star fa-xl"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">Wishlist Saya</h3>
                        <p class="text-muted mb-0 small">Daftar buku yang kamu simpan atau sedang kamu tunggu ketersediaannya.</p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i>Cari Buku Lain
                </a>
            </div>

            <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-lightbulb fa-lg me-3"></i>
                <div class="small text-dark">
                    <strong>Tips:</strong> Buku yang berstatus <span class="badge bg-success text-white">Tersedia</span> bisa langsung kamu pinjam tanpa harus mengantre lagi!
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Informasi Buku</th>
                                    <th>Ditambahkan</th>
                                    <th>Status Saat Ini</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($conn, "
                                    SELECT w.id_wishlist, w.created_at, k.id_koleksi, k.judul, k.penulis, g.nama_kategori 
                                    FROM wishlist w 
                                    JOIN koleksi k ON w.id_koleksi = k.id_koleksi 
                                    LEFT JOIN kategori g ON k.id_kategori = g.id_kategori
                                    WHERE w.id_anggota = '$id_anggota'
                                    ORDER BY w.created_at DESC
                                ");

                                if(mysqli_num_rows($q) > 0):
                                    while ($r = mysqli_fetch_assoc($q)):
                                        $id_buku = $r['id_koleksi'];
                                        
                                        // Cek ketersediaan real-time
                                        $cek_tersedia = mysqli_query($conn, "SELECT * FROM peminjaman p JOIN detail_peminjaman d ON p.id_peminjaman=d.id_peminjaman WHERE d.id_koleksi='$id_buku' AND p.status='Dipinjam'");
                                        $tersedia = (mysqli_num_rows($cek_tersedia) == 0);
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="book-icon">
                                                <i class="fa-solid fa-bookmark"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= $r['judul']; ?></div>
                                                <span class="badge bg-light text-muted border small" style="font-size: 0.65rem;">
                                                    <?= $r['nama_kategori'] ?? 'Umum'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y', strtotime($r['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if($tersedia): ?>
                                            <span class="status-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                <i class="fa-solid fa-circle-check me-2"></i> Tersedia
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                <i class="fa-solid fa-clock me-2"></i> Dipinjam
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <?php if($tersedia): ?>
                                                <a href="pinjam.php?id=<?= $id_buku ?>" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold shadow-sm">
                                                    Pinjam Sekarang
                                                </a>
                                            <?php endif; ?>
                                            <a href="wishlist_hapus.php?id=<?= $r['id_wishlist'] ?>" 
                                               class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                               onclick="return confirm('Yakin ingin menghapus dari wishlist?')">
                                               <i class="fa fa-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <h5 class="fw-bold">Wishlist Kamu Kosong</h5>
                                        <p class="text-muted small px-5 mb-3">Belum ada buku yang kamu simpan. Yuk, jelajahi koleksi menarik kami sekarang!</p>
                                        <a href="index.php" class="btn btn-primary rounded-pill px-4">Jelajahi Katalog</a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="text-center py-5 mt-3">
                <p class="text-muted small mb-0">© <?= date('Y'); ?> Libook Digital Library. Built for Professionalism.</p>
            </footer>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>