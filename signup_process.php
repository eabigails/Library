<?php
session_start();
include "config/database.php";

$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
$no_telp  = mysqli_real_escape_string($conn, $_POST['no_telp']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$tgl_skrg = date('Y-m-d');

$cek = mysqli_query($conn, "SELECT * FROM user_login WHERE username = '$username'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: signup.php?error=1");
    exit;
}

$q = mysqli_query($conn, "SELECT id_anggota FROM anggota ORDER BY id_anggota DESC LIMIT 1");
$data_agt = mysqli_fetch_assoc($q);
if ($data_agt) {
    $lastId = (int) substr($data_agt['id_anggota'], 3);
    $id_anggota = "AGT" . str_pad($lastId + 1, 3, "0", STR_PAD_LEFT);
} else {
    $id_anggota = "AGT001";
}

$query_agt = "INSERT INTO anggota (id_anggota, nama, alamat, no_telp, email, tgl_bergabung) 
              VALUES ('$id_anggota', '$nama', '$alamat', '$no_telp', '$email', '$tgl_skrg')";

$query_login = "INSERT INTO user_login (username, password, role, ref_id) 
                VALUES ('$username', '$password', 'Anggota', '$id_anggota')";

if (mysqli_query($conn, $query_agt) && mysqli_query($conn, $query_login)) {
    $_SESSION['login'] = true;
    $_SESSION['role']  = 'Anggota';
    $_SESSION['ref_id'] = $id_anggota;
    $_SESSION['username'] = $username;

    echo "<script>alert('Pendaftaran Berhasil!'); window.location='index.php';</script>";
} else {
    echo "Gagal mendaftar: " . mysqli_error($conn);
}
exit;