<?php
include "../../config/database.php";

$id = $_GET['id'];

// 1. Cari nama file gambar dulu sebelum datanya dihapus
$query_gambar = mysqli_query($conn, "SELECT gambar FROM koleksi WHERE id_koleksi = '$id'");
$data = mysqli_fetch_assoc($query_gambar);

if ($data) {
    $nama_file = $data['gambar'];
    
    // 2. Hapus file fisik di folder jika bukan default.jpg
    if ($nama_file != "default.jpg" && file_exists("../uploads/koleksi/" . $nama_file)) {
        unlink("../uploads/koleksi/" . $nama_file);
    }

    // 3. Hapus data di database
    $delete = mysqli_query($conn, "DELETE FROM koleksi WHERE id_koleksi = '$id'");

    if ($delete) {
        echo "<script>
                alert('Buku berhasil dihapus!');
                window.location='../../admin/admin_buku.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='admin_buku.php';</script>";
    }
}
?>