<?php
/**
 * login.php
 * Halaman form login
 */

// Cek apakah user sudah login
session_start();
if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Website Input Nilai Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 440px;
        }

        .login-card .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .login-card .logo i {
            font-size: 60px;
            color: #667eea;
        }

        .login-card h3 {
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-card p {
            color: #888;
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
            color: white;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>

    <div class="login-card animate__animated animate__fadeInDown">
        <!-- Logo -->
        <div class="logo">
            <i class="bi bi-mortarboard-fill" style="font-size: 60px; color: #667eea;">🎓</i>
        </div>
        <h3>Input Nilai Siswa</h3>
        <p>Silakan login untuk melanjutkan</p>

        <!-- Form Login -->
        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                    autocomplete="off"
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" name="login" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert untuk notifikasi error -->
    <?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '<?= $_SESSION['error'] ?>',
            confirmButtonColor: '#667eea'
        });
    </script>
    <?php unset($_SESSION['error']); endif; ?>

    <!-- Notifikasi setelah logout -->
    <script>
    // Cek apakah ada pesan logout
    if (sessionStorage.getItem('logoutMessage')) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Logout',
            text: sessionStorage.getItem('logoutMessage'),
            confirmButtonColor: '#667eea'
        });
        sessionStorage.removeItem('logoutMessage');
    }
    </script>
    
</body>
</html>