<?php
session_start();
include "config/database.php";

$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$password = trim($_POST['password']);

$query = mysqli_query($conn, "SELECT * FROM user_login WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['login']    = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['ref_id']   = $user['ref_id'];

    
        if ($user['role'] == 'Admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        echo "<script>alert('Password Admin Salah!'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Username Admin Tidak Ditemukan!'); window.location='login.php';</script>";
}
?>