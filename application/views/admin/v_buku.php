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
        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-tachometer-alt me-2"></i> Dashboard</a>
        <a class="nav-link active" href="<?= base_url('admin/buku') ?>"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link" href="<?= base_url('admin/peminjaman') ?>"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="<?= base_url('admin/anggota') ?>"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📚 Kelola Koleksi Buku</h2>
        <a href="<?= base_url('admin/buku_tambah') ?>" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Tambah Buku</a>
    </div>

    <?php if($this->session->flashdata('msg')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('msg') ?></div>
    <?php endif; ?>

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
                    <?php foreach($buku as $row) : ?>
                    <tr>
                        <td class="ps-4">
                            <?php 
                                $img = base_url('uploads/koleksi/' . $row['gambar']);
                                if (empty($row['gambar']) || !file_exists(FCPATH . 'uploads/koleksi/' . $row['gambar'])) {
                                    $img = base_url('assets/img/default_buku.jpg');
                                }
                            ?>
                            <img src="<?= $img ?>" style="width: 50px; height: 70px; object-fit: cover;" class="rounded shadow-sm">
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= $row['id_koleksi'] ?></span></td>
                        <td>
                            <div class="fw-bold"><?= $row['judul'] ?></div>
                            <small class="text-muted"><?= $row['penulis'] ?></small>
                        </td>
                        <td><span class="badge bg-info-subtle text-info"><?= $row['nama_kategori'] ?? 'Umum' ?></span></td>
                        <td><?= $row['tahun_terbit'] ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/buku_hapus/'.$row['id_koleksi']) ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Yakin ingin menghapus buku ini?')">
                               <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>