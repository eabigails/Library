<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | Libook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card shadow-sm border-0 my-5" style="width:420px;">
    <div class="card-body p-4">
        <div class="text-center mb-3">
            <img src="<?= base_url('assets/img/logo.png') ?>" width="70">
            <h4 class="fw-bold mt-2">Daftar Anggota</h4>
            <p class="text-muted small">Libook – Perpustakaan Digital</p>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger py-2 text-center small">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('index.php/auth/proses_signup') ?>">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="no_telp" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Sudah punya akun? <a href="<?= base_url('index.php/auth/login') ?>">Login</a></small>
        </div>
        <div class="text-center mt-2">
            <a href="<?= base_url('index.php/katalog'); ?>" class="text-decoration-none small">
                ← Kembali ke Home
            </a>
        </div>
    </div>
</div>
</body>
</html>