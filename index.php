<?php
session_start();
include "config/database.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libook | Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .navbar { border-bottom: 1px solid #eee; }
        .card { 
            transition: all 0.3s ease; 
            border: none; 
            border-radius: 12px; 
            overflow: hidden;
        }
        .card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important; 
        }
        .card-img-top {
            transition: all 0.5s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .btn { border-radius: 8px; font-weight: 600; }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="index.php">
            <img src="assets/img/logo.png" width="40" height="40" class="me-2" alt="Logo">Libook
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <?php if (!isset($_SESSION['login'])): ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                <?php elseif ($_SESSION['role'] == 'Anggota'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-1"></i> Hi, <?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?> 👋
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="riwayat.php"><i class="fa fa-file-text me-2"></i> Riwayat Pinjam</a></li>
                            <li><a class="dropdown-item" href="wishlist.php"><i class="fa fa-star me-2 text-warning"></i> Wishlist Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
    <li class="nav-item">
        <a class="btn btn-danger text-white px-4 shadow-sm" href="admin/admin_dashboard.php">
            <i class="fa fa-gauge-high me-2"></i> Kembali ke Panel Admin
        </a>
    </li>
<?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5 text-center">
    <h1 class="fw-bold">Katalog Libook</h1>
    <p class="text-muted">Jelajahi ribuan koleksi buku digital terbaik untuk masa depanmu</p>
</div>

<div class="container mb-5">
    <form method="GET" class="row g-3 bg-white p-4 rounded shadow-sm border">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Cari Buku</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Ketik judul atau penulis..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php
                $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");
                while ($k = mysqli_fetch_assoc($kat)):
                ?>
                    <option value="<?= $k['id_kategori']; ?>" <?= (($_GET['kategori'] ?? '') == $k['id_kategori']) ? 'selected' : '' ?>>
                        <?= $k['nama_kategori']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid align-items-end">
            <button class="btn btn-primary py-2 shadow-sm">Temukan</button>
        </div>
    </form>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php
        $where = [];
        if (!empty($_GET['q'])) {
            $q = mysqli_real_escape_string($conn, $_GET['q']);
            $where[] = "(k.judul LIKE '%$q%' OR k.penulis LIKE '%$q%')";
        }
        if (!empty($_GET['kategori'])) {
            $kat = mysqli_real_escape_string($conn, $_GET['kategori']);
            $where[] = "k.id_kategori = '$kat'";
        }
        $whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

        $query = mysqli_query($conn, "
            SELECT k.*, g.nama_kategori
            FROM koleksi k
            LEFT JOIN kategori g ON k.id_kategori = g.id_kategori
            $whereSQL
            ORDER BY k.judul ASC
        ");

        if (mysqli_num_rows($query) > 0):
            while ($buku = mysqli_fetch_assoc($query)):
                $gambar = $buku['gambar'] ?: 'default.jpg';
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="overflow-hidden">
                    <img src="uploads/koleksi/<?= $gambar ?>" class="card-img-top" style="height: 300px; object-fit:contain;" alt="Cover Buku">
                </div>
                <div class="card-body pb-0">
                    <span class="badge bg-primary-subtle text-primary mb-2">
                        <?= $buku['nama_kategori'] ?? 'Tanpa Kategori'; ?>
                    </span>
                    <h6 class="fw-bold mb-1 text-truncate-2" title="<?= $buku['judul']; ?>">
                        <?= $buku['judul']; ?>
                    </h6>
                    <p class="small text-muted mb-0"><i class="fa fa-user-edit me-1"></i> <?= $buku['penulis']; ?></p>
                    <p class="small text-muted"><i class="fa fa-calendar-alt me-1"></i> <?= $buku['tahun_terbit']; ?></p>
                </div>

                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                    <?php 
                    $id_buku = $buku['id_koleksi'];
                    // Cek ketersediaan buku
                    $cek_tersedia = mysqli_query($conn, "SELECT * FROM peminjaman p JOIN detail_peminjaman d ON p.id_peminjaman=d.id_peminjaman WHERE d.id_koleksi='$id_buku' AND p.status='Dipinjam'");
                    
                    if (!isset($_SESSION['login'])): ?>
                        <a href="login.php" class="btn btn-outline-primary btn-sm w-100 mt-2">Login untuk Pinjam</a>
                    <?php elseif ($_SESSION['role'] == 'Anggota'): ?>
                        <?php if (mysqli_num_rows($cek_tersedia) > 0): ?>
                            <a href="pinjam.php?id=<?= $id_buku ?>" class="btn btn-warning btn-sm w-100 mt-2 text-white">
                                <i class="fa fa-star me-1"></i> Wishlist
                            </a>
                        <?php else: ?>
                            <a href="pinjam.php?id=<?= $id_buku ?>" class="btn btn-success btn-sm w-100 mt-2 shadow-sm">
                                <i class="fa fa-book-open me-1"></i> Pinjam Sekarang
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-light btn-sm w-100 mt-2 disabled">Mode Admin</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            endwhile;
        else:
        ?>
            <div class="col-12 text-center py-5"> 
                <p class="text-muted fs-5">Ups! Buku tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center py-4 bg-white border-top text-muted">
    <div class="container">
        © <?= date('Y'); ?> <span class="fw-bold text-primary">Libook</span> – Perpustakaan Digital Berbasis LSP
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>