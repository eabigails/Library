<?php
session_start();
include "config/database.php";

$id_wishlist = $_GET['id'] ?? '';
$id_anggota = $_SESSION['ref_id'] ?? null;

$kembali = $_SERVER['HTTP_REFERER'] ?? 'wishlist.php';

if ($id_wishlist && $id_anggota) {

    $query = "DELETE FROM wishlist WHERE id_wishlist = '$id_wishlist' AND id_anggota = '$id_anggota'";
    $exec = mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {

        header("Location: " . $kembali);
    } else {
 
        echo "<script>
                alert('Gagal menghapus! Data tidak ditemukan atau session habis.');
                window.location.href='$kembali';
              </script>";
    }
} else {
    header("Location: wishlist.php");
}
exit;