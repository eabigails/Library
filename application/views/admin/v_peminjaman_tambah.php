<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catat Peminjaman | Libook Admin</title>
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
        <a class="nav-link" href="<?= base_url('admin/buku') ?>"><i class="fa fa-book me-2"></i> Kelola Buku</a>
        <a class="nav-link active" href="<?= base_url('admin/peminjaman') ?>"><i class="fa fa-exchange-alt me-2"></i> Peminjaman</a>
        <a class="nav-link" href="<?= base_url('admin/anggota') ?>"><i class="fa fa-users me-2"></i> Data Anggota</a>
        <a class="nav-link text-danger mt-5" href="<?= base_url('auth/logout') ?>"><i class="fa fa-sign-out-alt me-2"></i> Log out</a>
    </nav>
</div>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h3 class="fw-bold text-success mb-4"><i class="fa fa-plus-circle me-2"></i> Catat Peminjaman Baru</h3>
                    
                    <form action="<?= base_url('admin/proses_peminjaman') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ID Peminjaman</label>
                            <input type="text" name="id_peminjaman" class="form-control bg-light" value="<?= $newId ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Anggota</label>
                            <select name="id_anggota" class="form-select" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php foreach($anggota as $a): ?>
                                    <option value="<?= $a['id_anggota'] ?>"><?= $a['nama'] ?> (<?= $a['id_anggota'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Jika nama tidak ada, pastikan anggota sudah terdaftar.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pilih Buku</label>
                            <select name="id_koleksi" class="form-select" required>
                                <option value="">-- Pilih Judul Buku --</option>
                                <?php foreach($buku as $b): ?>
                                    <option value="<?= $b['id_koleksi'] ?>"><?= $b['judul'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Hanya menampilkan buku yang tersedia (tidak sedang dipinjam).</small>
                        </div>

                        <div class="alert alert-info py-2">
                            <i class="fa fa-info-circle me-2"></i> Peminjaman berlaku selama 7 hari sejak hari ini.
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="<?= base_url('admin/peminjaman') ?>" class="btn btn-light px-4 text-secondary">Batal</a>
                            <button type="submit" class="btn btn-success px-5 shadow-sm">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>