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
            width: 40px; height: 40px;
            background: #eef2ff; color: #4f46e5;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; margin-right: 15px;
        }
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
    <div class="row">
        <div class="col-lg-11 mx-auto">
            
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('katalog'); ?>" class="text-decoration-none text-muted small">Katalog</a></li>
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
                <a href="<?= base_url('katalog'); ?>" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Katalog
                </a>
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
                                <?php if(!empty($riwayat)): ?>
                                    <?php foreach ($riwayat as $r): 
                                        $is_dipinjam = ($r['status'] == 'Dipinjam');
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark"><?= $r['id_peminjaman']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="book-icon"><i class="fa-solid fa-book"></i></div>
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
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <h5 class="fw-bold">Belum Ada Riwayat</h5>
                                            <p class="text-muted small px-5">Kamu belum meminjam buku apa pun.</p>
                                            <a href="<?= base_url('katalog'); ?>" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Pinjam Buku</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer class="text-center py-5">
                <p class="text-muted small mb-0">© <?= date('Y'); ?> Libook Digital Library.</p>
            </footer>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>