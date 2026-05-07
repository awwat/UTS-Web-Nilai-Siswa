<?php
/**
 * dashboard.php
 * Halaman utama dashboard admin
 */

// Cek session
require_once 'session_check.php';

// Include koneksi
require_once 'koneksi.php';

// Query statistik
// 1. Total siswa
$q_total = "SELECT COUNT(*) AS total FROM siswa";
$result_total = mysqli_query($koneksi, $q_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_siswa = $row_total['total'];

// 2. Rata-rata nilai
$q_rata = "SELECT ROUND(AVG(nilai), 2) AS rata_rata FROM siswa";
$result_rata = mysqli_query($koneksi, $q_rata);
$row_rata = mysqli_fetch_assoc($result_rata);
$rata_nilai = $row_rata['rata_rata'] ?? 0;

// 3. Jumlah mata pelajaran
$q_mapel = "SELECT COUNT(DISTINCT mata_pelajaran) AS jumlah FROM siswa";
$result_mapel = mysqli_query($koneksi, $q_mapel);
$row_mapel = mysqli_fetch_assoc($result_mapel);
$jumlah_mapel = $row_mapel['jumlah'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Website Input Nilai Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Navbar Top -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">

            <!-- Welcome Card -->
            <div class="card-welcome animate__animated animate__fadeInUp mb-4">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">Selamat Datang, <?= $_SESSION['nama'] ?>! 👋</h3>
                        <p class="text-muted mb-0">Berikut adalah ringkasan data nilai siswa Anda hari ini.</p>
                    </div>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="row g-4">
                <!-- Total Siswa -->
                <div class="col-12 col-md-4">
                    <div class="stat-card animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-box" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h2 class="text-primary"><?= $total_siswa ?></h2>
                        </div>
                        <p>Total Siswa</p>
                    </div>
                </div>

                <!-- Rata-rata Nilai -->
                <div class="col-12 col-md-4">
                    <div class="stat-card animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-box" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h2 class="text-danger"><?= $rata_nilai ?></h2>
                        </div>
                        <p>Rata-rata Nilai</p>
                    </div>
                </div>

                <!-- Jumlah Mata Pelajaran -->
                <div class="col-12 col-md-4">
                    <div class="stat-card animate__animated animate__fadeInUp animate__delay-3s">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-box" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                                <i class="bi bi-book-fill"></i>
                            </div>
                            <h2 class="text-success"><?= $jumlah_mapel ?></h2>
                        </div>
                        <p>Mata Pelajaran</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Jam Real-time -->
    <script>
        function updateJam() {
            const now = new Date();
            const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('jam').textContent = '🕐 ' + jam;
        }
        setInterval(updateJam, 1000);
        updateJam();
    </script>

    <!-- Script Toggle Sidebar Mobile -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Tutup sidebar jika klik di luar (mobile)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>

</body>
</html>
<?php
// Tutup koneksi
mysqli_close($koneksi);
?>