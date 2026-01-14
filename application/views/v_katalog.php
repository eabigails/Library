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
        .card { transition: all 0.3s ease; border: none; border-radius: 12px; overflow: hidden; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important; }
        .card-img-top { transition: all 0.5s ease; }
        .card:hover .card-img-top { transform: scale(1.05); }
        .btn { border-radius: 8px; font-weight: 600; }
        .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="<?= base_url() ?>">
            <img src="<?= base_url('assets/img/logo.png') ?>" width="40" height="40" class="me-2" alt="Logo">Libook
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <?php if (!$this->session->userdata('login')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('index.php/auth/login') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('index.php/auth/signup') ?>">Sign Up</a>
                    </li>
                <?php elseif ($this->session->userdata('role') == 'Anggota'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-1"></i> Hi, <?= $this->session->userdata('username'); ?> 👋
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="<?= base_url('riwayat') ?>"><i class="fa fa-file-text me-2"></i> Riwayat</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('wishlist') ?>"><i class="fa fa-star text-warning me-2"></i> Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-danger btn-sm text-white px-4" href="<?= base_url('admin/dashboard') ?>">Panel Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i> <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i> <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<div class="container my-5 text-center">
    <h1 class="fw-bold">Katalog Libook</h1>
    <p class="text-muted">Jelajahi koleksi buku digital terbaik</p>
</div>

<div class="container mb-5">
    <form method="GET" action="<?= base_url('katalog') ?>" class="row g-3 bg-white p-4 rounded shadow-sm border">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Cari Buku</label>
            <input type="text" name="q" class="form-control" placeholder="Judul atau penulis..." value="<?= $this->input->get('q') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori_list as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>" <?= ($this->input->get('kategori') == $k['id_kategori']) ? 'selected' : '' ?>>
                        <?= $k['nama_kategori'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid align-items-end">
            <button class="btn btn-primary py-2">Cari</button>
        </div>
    </form>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php if (!empty($buku_list)): ?>
            <?php foreach ($buku_list as $buku): 
                $gambar = $buku['gambar'] ? $buku['gambar'] : 'default.jpg';
                $tersedia = $M_Katalog->cek_tersedia($buku['id_koleksi'])->num_rows();
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm">
                    <img src="<?= base_url('uploads/koleksi/' . $gambar) ?>" class="card-img-top" style="height: 300px; object-fit:contain;">
                    <div class="card-body">
                        <span class="badge bg-primary-subtle text-primary mb-2"><?= $buku['nama_kategori'] ?></span>
                        <h6 class="fw-bold text-truncate-2"><?= $buku['judul'] ?></h6>
                        <p class="small text-muted mb-0"><?= $buku['penulis'] ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <?php if (!$this->session->userdata('login')): ?>
                            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-primary btn-sm w-100">Login untuk Pinjam</a>
                        <?php else: ?>
                            <?php if ($tersedia > 0): // Artinya ada data di tabel peminjaman dengan status 'Dipinjam' ?>
                                <a href="<?= base_url('peminjaman/proses_pinjam/'.$buku['id_koleksi']); ?>" 
                                   class="btn btn-warning btn-sm w-100 text-white shadow-sm"
                                   onclick="return confirm('Buku sedang dipinjam. Masukkan ke wishlist untuk antre?')">
                                   <i class="fa fa-star me-1"></i> Wishlist
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('peminjaman/proses_pinjam/'.$buku['id_koleksi']); ?>" 
                                   class="btn btn-primary btn-sm w-100 shadow-sm" 
                                   onclick="return confirm('Pinjam buku ini selama 7 hari?')">
                                   Pinjam Sekarang
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center py-5">Buku tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center py-4 bg-white border-top text-muted">
    © <?= date('Y'); ?> <span class="fw-bold text-primary">Libook</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>