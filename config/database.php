<?php
$host = "localhost";
$user = "root";
$pass = "ujinujin";
$db   = "db_perpustakaan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
