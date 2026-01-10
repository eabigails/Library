<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Libook</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card shadow-sm border-0" style="width:380px;">
    <div class="card-body p-4">

        <div class="text-center mb-3">
            <img src="assets/img/logo.png" width="70">
            <h4 class="fw-bold mt-2">Libook</h4>
            <p class="text-muted small">Perpustakaan Digital</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 text-center">
                Username atau password salah
            </div>
        <?php endif; ?>

        <form method="POST" action="login_process.php">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text"
                       name="username"
                       class="form-control"
                       placeholder="Masukkan username"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Masukkan password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>

        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                Belum punya akun?
                <a href="signup.php" class="text-decoration-none">Sign Up</a>
            </small>
        </div>

        <div class="text-center mt-2">
            <a href="index.php" class="text-decoration-none small">
                ← Kembali ke Home
            </a>
        </div>

    </div>
</div>

</body>
</html>
