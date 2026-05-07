<?php
/**
 * koneksi.php
 * Koneksi database dengan MySQLi
 */

$host     = "localhost";
$username = "root";
$password = "";
$database = "akademik";

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke utf8mb4
mysqli_set_charset($koneksi, "utf8mb4");

// Fungsi sanitasi input
function bersihkan_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>