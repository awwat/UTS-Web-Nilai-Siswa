<?php
/**
 * hapus_siswa.php
 * Proses hapus data siswa
 */

// Cek session
require_once 'session_check.php';

// Include koneksi
require_once 'koneksi.php';

// Cek parameter id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_hapus'] = "ID siswa tidak ditemukan!";
    header("Location: lihat_siswa.php");
    exit();
}

$id_siswa = (int) $_GET['id'];

// Ambil data siswa sebelum dihapus (untuk pesan notifikasi)
$query_cek = "SELECT nama_siswa FROM siswa WHERE id_siswa = ?";
$stmt_cek = mysqli_prepare($koneksi, $query_cek);
mysqli_stmt_bind_param($stmt_cek, "i", $id_siswa);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);

// Jika data tidak ditemukan
if (mysqli_num_rows($result_cek) === 0) {
    $_SESSION['error_hapus'] = "Data siswa tidak ditemukan!";
    mysqli_stmt_close($stmt_cek);
    mysqli_close($koneksi);
    header("Location: lihat_siswa.php");
    exit();
}

$data_siswa = mysqli_fetch_assoc($result_cek);
$nama_siswa = $data_siswa['nama_siswa'];
mysqli_stmt_close($stmt_cek);

// Proses hapus data
$query_hapus = "DELETE FROM siswa WHERE id_siswa = ?";
$stmt_hapus = mysqli_prepare($koneksi, $query_hapus);
mysqli_stmt_bind_param($stmt_hapus, "i", $id_siswa);

if (mysqli_stmt_execute($stmt_hapus)) {
    // Simpan pesan sukses ke session
    $_SESSION['sukses'] = "Data siswa <b>$nama_siswa</b> berhasil dihapus!";
} else {
    $_SESSION['error_hapus'] = "Gagal menghapus data: " . mysqli_error($koneksi);
}

// Tutup statement & koneksi
mysqli_stmt_close($stmt_hapus);
mysqli_close($koneksi);

// Redirect ke halaman lihat siswa
header("Location: lihat_siswa.php");
exit();
?>