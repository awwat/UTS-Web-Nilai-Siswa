<?php
/**
 * session_check.php
 * Cek session untuk proteksi halaman
 * Include file ini di semua halaman yang butuh login
 */

// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    // User belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Jika sudah login, data user tersedia di $_SESSION
// $_SESSION['id_user'], $_SESSION['username'], $_SESSION['nama'], $_SESSION['hakakses']
?>