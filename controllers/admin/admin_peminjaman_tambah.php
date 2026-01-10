<?php
session_start();
include "../../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT id_peminjaman FROM peminjaman ORDER BY id_peminjaman DESC LIMIT 1");
$data = mysqli_fetch_assoc($q);
if ($data) {
    $lastId = (int) substr($data['id_peminjaman'], 2);
    $newId  = "PJ" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
} else {
    $newId = "PJ001";
}

if (isset($_POST['pinjam'])) {
    $id_pjm     = $newId;
    $id_agt     = $_POST['id_anggota'];
    $id_koleksi = $_POST['id_koleksi'];
    $tgl_pinjam = date('Y-m-d');
    $tgl_kmbali = date('Y-m-d', strtotime('+7 days'));
    $id_kry     = $_SESSION['ref_id']; // Menggunakan ref_id sesuai session login

    // --- VALIDASI DOUBLE PINJAM (Proteksi Sisi Server) ---
    $cek_buku = mysqli_query($conn, "SELECT dp.id_koleksi 
                                     FROM detail_peminjaman dp 
                                     JOIN peminjaman p ON dp.id_peminjaman = p.id_peminjaman 
                                     WHERE dp.id_koleksi = '$id_koleksi' AND p.status = 'Dipinjam'");

    if (mysqli_num_rows($cek_buku) > 0) {
        echo "<script>alert('Gagal! Buku ini baru saja dipinjam orang lain atau statusnya belum kembali.'); window.location='admin_peminjaman_tambah.php';</script>";
        exit;
    }

    // Insert ke tabel peminjaman
    $sql1 = "INSERT INTO peminjaman (id_peminjaman, id_anggota, id_karyawan, tgl_pinjam, tgl_kembali, status) 
             VALUES ('$id_pjm', '$id_agt', '$id_kry', '$tgl_pinjam', '$tgl_kmbali', 'Dipinjam')";
    
    // Insert ke tabel detail_peminjaman
    $sql2 = "INSERT INTO detail_peminjaman (id_peminjaman, id_koleksi) 
             VALUES ('$id_pjm', '$id_koleksi')";

    if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)) {
        echo "<script>alert('Peminjaman Berhasil Dicatat!'); window.location='../../admin/admin_peminjaman.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Pinjaman Manual | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary"><i class="fa fa-plus-circle me-2"></i>Tambah Peminjaman Manual</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">ID Transaksi</label>
                                <input type="text" class="form-control bg-light" value="<?= $newId ?>" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Anggota</label>
                                <select name="id_anggota" class="form-select" required>
                                    <option value="">-- Cari Nama Anggota --</option>
                                    <?php
                                    $agt = mysqli_query($conn, "SELECT * FROM anggota ORDER BY nama ASC");
                                    while($a = mysqli_fetch_assoc($agt)) {
                                        echo "<option value='{$a['id_anggota']}'> {$a['nama']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Buku</label>
                                <select name="id_koleksi" class="form-select" required>
                                    <option value="">-- Cari Judul Buku --</option>
                                    <?php
                                    // QUERY FILTER: Hanya tampilkan buku yang sedang TIDAK dipinjam
                                    $query_buku = "SELECT * FROM koleksi 
                                                   WHERE id_koleksi NOT IN (
                                                       SELECT dp.id_koleksi 
                                                       FROM detail_peminjaman dp 
                                                       JOIN peminjaman p ON dp.id_peminjaman = p.id_peminjaman 
                                                       WHERE p.status = 'Dipinjam'
                                                   ) 
                                                   ORDER BY judul ASC";
                                    
                                    $buku = mysqli_query($conn, $query_buku);
                                    while($b = mysqli_fetch_assoc($buku)) {
                                        echo "<option value='{$b['id_koleksi']}'>{$b['judul']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="form-text text-muted">*Buku yang sedang dipinjam tidak akan muncul di daftar.</div>
                            </div>

                            <div class="alert alert-info small border-0">
                                <i class="fa fa-info-circle me-1"></i> Tanggal kembali otomatis diset 7 hari (<?= date('d/m/Y', strtotime('+7 days')) ?>).
                            </div>

                            <div class="d-flex gap-2">
                                <a href="admin_peminjaman.php" class="btn btn-light w-50">Batal</a>
                                <button type="submit" name="pinjam" class="btn btn-primary w-50 shadow-sm">Simpan Transaksi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>