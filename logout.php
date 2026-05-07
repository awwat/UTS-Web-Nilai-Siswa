<?php
/**
 * logout.php
 * Menghapus session dan logout user
 */

// Mulai session
session_start();

// Hapus semua session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login dengan notifikasi
echo "<script>
    sessionStorage.setItem('logoutMessage', 'Anda berhasil logout!');
    window.location.href = 'login.php';
</script>";
exit();
?>