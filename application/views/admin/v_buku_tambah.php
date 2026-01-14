<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku | Libook Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #2c3e50; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #bdc3c7; padding: 15px 20px; text-decoration: none; display: block; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
        .card { border-radius: 15px; border: none; }
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm p-4">
                    <h3 class="fw-bold text-primary mb-4">➕ Tambah Koleksi Buku</h3>
                    
                    <form action="<?= base_url('admin/proses_tambah_buku') ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">ID Buku</label>
                                <input type="text" name="id_koleksi" class="form-control bg-light" value="<?= $newId ?>" readonly>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Judul Buku</label>
                                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Penulis</label>
                                <input type="text" name="penulis" class="form-control" placeholder="Nama penulis">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($kategori as $k): ?>
                                        <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" class="form-control" value="<?= date('Y') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Cover Buku (Gambar)</label>
                            <input type="file" name="gambar" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="<?= base_url('admin/buku') ?>" class="btn btn-light px-4 text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">Simpan Buku</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>