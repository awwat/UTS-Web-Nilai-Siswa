<?php
/**
 * proses_login.php
 * Memproses login user
 */

// Mulai session
session_start();

// Include koneksi database
require_once 'koneksi.php';

// Cek apakah tombol login ditekan
if (isset($_POST['login'])) {

    // Ambil input dari form
    $username = bersihkan_input($_POST['username']);
    $password = $_POST['password']; // Password tidak dibersihkan karena akan diverifikasi

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan password wajib diisi!";
        header("Location: login.php");
        exit();
    }

    // Query cek user dengan prepared statement
    $query = "SELECT id_user, username, password_hash, nama, hakakses FROM user WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Cek apakah username ditemukan
    if (mysqli_num_rows($result) === 1) {

        // Ambil data user
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row['password_hash'])) {

            // Password benar, buat session
            $_SESSION['id_user']   = $row['id_user'];
            $_SESSION['username']  = $row['username'];
            $_SESSION['nama']      = $row['nama'];
            $_SESSION['hakakses']  = $row['hakakses'];

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            // Password salah
            $_SESSION['error'] = "Password yang Anda masukkan salah!";
            header("Location: login.php");
            exit();
        }

    } else {
        // Username tidak ditemukan
        $_SESSION['error'] = "Username tidak ditemukan!";
        header("Location: login.php");
        exit();
    }

    // Tutup statement
    mysqli_stmt_close($stmt);

} else {
    // Jika tidak melalui tombol login, redirect ke login
    header("Location: login.php");
    exit();
}

// Tutup koneksi
mysqli_close($koneksi);
?>