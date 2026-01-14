<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
        /* Style Status Custom sesuai keinginanmu */
        .status-dipinjam { background-color: #fff3cd; color: #856404; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; }
        .status-kembali { background-color: #d4edda; color: #155724; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; }
        .status-terlambat { background-color: #f8d7da; color: #721c24; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; border: 1px solid #f5c6cb; }
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
        <a class="nav-link" href="#"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link active" href="<?= base_url('admin/peminjaman') ?>"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="<?= base_url('admin/anggota') ?>"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📋 Daftar Pinjaman Masuk</h2>
        <a href="<?= base_url('admin/peminjaman_tambah') ?>" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Tambah Manual</a>
    </div>

    <?php if($this->session->flashdata('msg')): ?>
        <div class="alert alert-success border-0 shadow-sm small py-2">
            <?= $this->session->flashdata('msg') ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Pinjam</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $today = date('Y-m-d');
                    // Ganti while menjadi foreach karena CI3 mengirim data berupa array
                    foreach($pinjaman as $row) : 
                        $deadline = $row['tgl_kembali'];
                    ?>
                    <tr>
                        <td><strong><?= $row['id_peminjaman'] ?></strong></td>
                        <td><?= $row['nama_anggota'] ?></td>
                        <td><?= $row['judul'] ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($deadline)) ?></td>
                        <td>
                            <?php 
                            if($row['status'] == 'Dipinjam') {
                                if($today > $deadline) {
                                    echo '<span class="status-terlambat fw-bold">Terlambat</span>';
                                } else {
                                    echo '<span class="status-dipinjam fw-bold">Dipinjam</span>';
                                }
                            } else {
                                echo '<span class="status-kembali fw-bold">Selesai</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php if($row['status'] == 'Dipinjam') : ?>
                                <a href="<?= base_url('admin/peminjaman_selesai/'.$row['id_peminjaman']) ?>" 
                                   class="btn btn-success btn-sm" onclick="return confirm('Konfirmasi buku kembali?')">
                                    Selesai
                                </a>
                            <?php else : ?>
                                <button class="btn btn-light btn-sm" disabled>Selesai</button>
                            <?php endif; ?>
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