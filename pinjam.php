<?php
session_start();
include "config/database.php";

$id_anggota = $_SESSION['ref_id'] ?? null;
$id_koleksi = $_GET['id'] ?? '';
$kembali = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if (!$id_anggota || !$id_koleksi) {
    header("Location: index.php");
    exit;
}

$cek_saya = mysqli_query($conn, "
    SELECT p.id_peminjaman 
    FROM peminjaman p 
    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
    WHERE p.id_anggota = '$id_anggota' 
    AND d.id_koleksi = '$id_koleksi' 
    AND p.status = 'Dipinjam'
");

if (mysqli_num_rows($cek_saya) > 0) {
    header("Location: " . $kembali);
    exit;
}


$cek_orang = mysqli_query($conn, "
    SELECT p.id_peminjaman 
    FROM peminjaman p 
    JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
    WHERE d.id_koleksi = '$id_koleksi' 
    AND p.status = 'Dipinjam'
");

if (mysqli_num_rows($cek_orang) > 0) {

    $cek_wishlist = mysqli_query($conn, "SELECT * FROM wishlist WHERE id_anggota='$id_anggota' AND id_koleksi='$id_koleksi'");
    
    if (mysqli_num_rows($cek_wishlist) == 0) {
        mysqli_query($conn, "INSERT INTO wishlist (id_anggota, id_koleksi) VALUES ('$id_anggota', '$id_koleksi')");
        echo "<script>
                alert('Buku sedang dipinjam orang lain. Otomatis masuk ke wishlist!');
                window.location.href='$kembali';
              </script>";
    } else {
        echo "<script>
                alert('Buku sudah ada di wishlist kamu.');
                window.location.href='$kembali';
              </script>";
    }
    exit;
}

$q = mysqli_query($conn, "SELECT id_peminjaman FROM peminjaman ORDER BY id_peminjaman DESC LIMIT 1");
$last = mysqli_fetch_assoc($q);
$no = $last ? intval(substr($last['id_peminjaman'], 2)) + 1 : 1;
$id_peminjaman = "PJ" . str_pad($no, 3, "0", STR_PAD_LEFT);

$tgl_pinjam  = date('Y-m-d');
$tgl_kembali = date('Y-m-d', strtotime('+7 days'));

mysqli_query($conn, "INSERT INTO peminjaman (id_peminjaman, id_anggota, tgl_pinjam, tgl_kembali, status) VALUES ('$id_peminjaman', '$id_anggota', '$tgl_pinjam', '$tgl_kembali', 'Dipinjam')");
mysqli_query($conn, "INSERT INTO detail_peminjaman (id_peminjaman, id_koleksi) VALUES ('$id_peminjaman', '$id_koleksi')");

header("Location: " . $kembali);
exit;
?>