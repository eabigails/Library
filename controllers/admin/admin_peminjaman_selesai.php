<?php
session_start();
include "../../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_pjm = $_GET['id'];
    $id_admin = $_SESSION['id_user'] ?? 'KR001'; 

    $query = "UPDATE peminjaman SET 
              status = 'Kembali', 
              id_karyawan = '$id_admin' 
              WHERE id_peminjaman = '$id_pjm'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Buku telah dikembalikan! Status diperbarui.');
                window.location='../../admin/admin_peminjaman.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_peminjaman.php");
}
?>