<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .status-badge { border-radius: 50px; padding: 6px 16px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; }
        .book-icon { width: 40px; height: 40px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; border-radius: 10px; margin-right: 15px; }
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

<div class="container py-4">
    <div class="bg-white p-4 rounded-4 shadow-sm border-start border-warning border-5 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3 text-warning">
                <i class="fa-solid fa-star fa-xl"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1">Wishlist Saya</h3>
                <p class="text-muted mb-0 small">Buku yang kamu tunggu ketersediaannya.</p>
            </div>
        </div>
        <a href="<?= base_url('katalog') ?>" class="btn btn-outline-primary rounded-pill btn-sm fw-semibold">
            <i class="fa-solid fa-plus me-2"></i>Cari Buku
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Informasi Buku</th>
                            <th>Ditambahkan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($wishlist)): ?>
                            <?php foreach($wishlist as $r): 
                                // Cek ketersediaan via Model yang dikirim dari controller
                                $is_tersedia = ($M_Katalog->cek_tersedia($r['id_koleksi'])->num_rows() == 0);
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="book-icon"><i class="fa-solid fa-bookmark"></i></div>
                                        <div>
                                            <div class="fw-bold"><?= $r['judul']; ?></div>
                                            <small class="text-muted"><?= $r['penulis']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= date('d M Y', strtotime($r['created_at'])); ?></td>
                                <td>
                                    <?php if($is_tersedia): ?>
                                        <span class="status-badge bg-success bg-opacity-10 text-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="status-badge bg-warning bg-opacity-10 text-warning">Dipinjam</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($is_tersedia): ?>
                                        <a href="<?= base_url('peminjaman/proses_pinjam/'.$r['id_koleksi']) ?>" class="btn btn-success btn-sm rounded-pill px-3">Pinjam</a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('anggota/hapus_wishlist/'.$r['id_wishlist']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Hapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <p class="text-muted">Wishlist kamu masih kosong.</p>
                                    <a href="<?= base_url('katalog') ?>" class="btn btn-primary btn-sm rounded-pill">Lihat Katalog</a>
                                </td>
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