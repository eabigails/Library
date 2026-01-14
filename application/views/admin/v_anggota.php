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
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="nav-link" href="<?= base_url('admin/buku') ?>"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="<?= base_url('admin/peminjaman') ?>"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link active" href="<?= base_url('admin/anggota') ?>"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Manajemen Anggota</h2>
            <div class="text-muted">Total: <?= count($anggota); ?> Anggota</div>
        </div>

        <?php if($this->session->flashdata('msg')): ?>
            <div class="alert alert-success border-0 shadow-sm"><?= $this->session->flashdata('msg') ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

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
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anggota as $row) : ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $row['id_anggota']; ?></span></td>
                            <td>
                                <div class="fw-bold"><?= $row['nama']; ?></div>
                                <small class="text-muted"><?= $row['email']; ?></small>
                            </td>
                            <td><?= $row['no_telp']; ?></td>
                            <td class="small text-truncate" style="max-width: 150px;"><?= $row['alamat']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl_bergabung'])); ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/anggota_hapus/'.$row['id_anggota']) ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Yakin ingin menghapus anggota ini? Akun login juga akan terhapus.')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($anggota)) : ?>
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