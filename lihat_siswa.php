<?php
/**
 * lihat_siswa.php
 * Halaman lihat & cari data siswa
 */

// Cek session
require_once 'session_check.php';

// Include koneksi
require_once 'koneksi.php';

// Tangkap keyword pencarian
$keyword = isset($_GET['cari']) ? bersihkan_input($_GET['cari']) : '';

// Query data siswa dengan pencarian
if (!empty($keyword)) {
    // Jika ada keyword, cari berdasarkan nama_siswa, nis, kelas, atau mata_pelajaran
    $query = "SELECT * FROM siswa WHERE nama_siswa LIKE ? OR nis LIKE ? OR kelas LIKE ? OR mata_pelajaran LIKE ? ORDER BY id_siswa DESC";
    $stmt = mysqli_prepare($koneksi, $query);
    $search_param = "%{$keyword}%";
    mysqli_stmt_bind_param($stmt, "ssss", $search_param, $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // Jika tidak ada keyword, tampilkan semua data
    $query = "SELECT * FROM siswa ORDER BY id_siswa DESC";
    $result = mysqli_query($koneksi, $query);
}

// Notifikasi sukses dari session (dari tambah/edit/hapus)
$sukses = '';
if (isset($_SESSION['sukses'])) {
    $sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']); // Hapus setelah ditampilkan
}

// Notifikasi error hapus dari session
$error_hapus = '';
if (isset($_SESSION['error_hapus'])) {
    $error_hapus = $_SESSION['error_hapus'];
    unset($_SESSION['error_hapus']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Website Input Nilai Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            <!-- Notifikasi Sukses -->
            <?php if (!empty($sukses)): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: '<?= $sukses ?>',
                    confirmButtonColor: '#667eea',
                    timer: 3000,
                    timerProgressBar: true
                });
            </script>
            <?php endif; ?>

            <!-- Notifikasi Error Hapus -->
            <?php if (!empty($error_hapus)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: '<?= $error_hapus ?>',
                    confirmButtonColor: '#667eea'
                });
            </script>
            <?php endif; ?>

            <!-- Judul & Tombol Tambah -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
                <h4 class="text-white fw-bold mb-2 mb-md-0">
                    <i class="bi bi-people-fill me-2"></i>Data Siswa
                </h4>
                <a href="tambah_siswa.php" class="btn btn-light fw-semibold shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Siswa
                </a>
            </div>

            <!-- Card Tabel -->
            <div class="card-custom animate__animated animate__fadeInUp">

                <!-- Form Pencarian -->
                <form method="GET" action="lihat_siswa.php" class="row g-2 mb-3">
                    <div class="col-12 col-md-8 col-lg-9">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius: 12px 0 0 12px;">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input
                                type="text"
                                name="cari"
                                class="form-control search-box border-start-0"
                                placeholder="Cari berdasarkan Nama atau NIS..."
                                value="<?= htmlspecialchars($keyword) ?>"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="col-6 col-md-2 col-lg-2">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                    <div class="col-6 col-md-2 col-lg-1">
                        <?php if (!empty($keyword)): ?>
                            <a href="lihat_siswa.php" class="btn btn-outline-secondary w-100" style="border-radius: 12px;">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Info Hasil Pencarian -->
                <?php if (!empty($keyword)): ?>
                    <div class="alert alert-info border-0" style="background: #f0f2ff; border-radius: 10px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Hasil pencarian untuk: <strong>"<?= htmlspecialchars($keyword) ?>"</strong>
                        (<?= mysqli_num_rows($result) ?> data ditemukan)
                    </div>
                <?php endif; ?>

                <!-- Tabel Data Siswa -->
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><span class="fw-semibold"><?= htmlspecialchars($row['nis']) ?></span></td>
                                        <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                                        <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
                                        <td class="text-center">
                                            <?php
                                                $nilai = $row['nilai'];
                                                if ($nilai >= 90) {
                                                    $warna = 'success';
                                                } elseif ($nilai >= 75) {
                                                    $warna = 'warning';
                                                } else {
                                                    $warna = 'danger';
                                                }
                                            ?>
                                            <span class="badge-nilai bg-<?= $warna ?>">
                                                <?= number_format($nilai, 2) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="edit_siswa.php?id=<?= $row['id_siswa'] ?>" class="btn btn-warning btn-sm btn-aksi me-1" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="hapus_siswa.php?id=<?= $row['id_siswa'] ?>" class="btn btn-danger btn-sm btn-aksi" title="Hapus" onclick="return konfirmasiHapus(event, '<?= htmlspecialchars($row['nama_siswa']) ?>')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 40px; display: block;"></i>
                                        Data tidak ditemukan
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
            const jamEl = document.getElementById('jam');
            if (jamEl) jamEl.textContent = '🕐 ' + jam;
        }
        setInterval(updateJam, 1000);
        updateJam();
    </script>

    <!-- Script Toggle Sidebar Mobile -->
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        }
    </script>

    <!-- SweetAlert Konfirmasi Hapus -->
    <script>
        function konfirmasiHapus(event, nama) {
            event.preventDefault();
            const url = event.currentTarget.getAttribute('href');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: 'Data siswa <b>"' + nama + '"</b> akan dihapus permanen dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="bi bi-x-circle me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>

</body>
</html>
<?php
// Tutup statement jika ada
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
// Tutup koneksi
mysqli_close($koneksi);
?>