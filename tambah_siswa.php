<?php
/**
 * tambah_siswa.php
 * Form tambah data siswa
 */

// Cek session
require_once 'session_check.php';

// Include koneksi
require_once 'koneksi.php';

// Proses simpan data
if (isset($_POST['simpan'])) {
    // Ambil & bersihkan input
    $nis             = bersihkan_input($_POST['nis']);
    $nama_siswa      = bersihkan_input($_POST['nama_siswa']);
    $kelas           = bersihkan_input($_POST['kelas']);
    $mata_pelajaran  = bersihkan_input($_POST['mata_pelajaran']);
    $nilai           = $_POST['nilai'];

    // Validasi input kosong
    $error = [];
    if (empty($nis))            $error[] = "NIS wajib diisi!";
    if (empty($nama_siswa))     $error[] = "Nama Siswa wajib diisi!";
    if (empty($kelas))          $error[] = "Kelas wajib diisi!";
    if (empty($mata_pelajaran)) $error[] = "Mata Pelajaran wajib diisi!";
    if ($nilai === '')          $error[] = "Nilai wajib diisi!";

    // Validasi nilai (0-100)
    if ($nilai !== '' && ($nilai < 0 || $nilai > 100)) {
        $error[] = "Nilai harus antara 0 - 100!";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($error)) {
        // Cek apakah NIS sudah ada
        $cek_query = "SELECT id_siswa FROM siswa WHERE nis = ?";
        $cek_stmt = mysqli_prepare($koneksi, $cek_query);
        mysqli_stmt_bind_param($cek_stmt, "s", $nis);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) > 0) {
            $error[] = "NIS <b>$nis</b> sudah terdaftar! Gunakan NIS lain.";
        } else {
            // Insert data dengan prepared statement
            $query = "INSERT INTO siswa (nis, nama_siswa, kelas, mata_pelajaran, nilai) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ssssd", $nis, $nama_siswa, $kelas, $mata_pelajaran, $nilai);

            if (mysqli_stmt_execute($stmt)) {
                // Simpan pesan sukses ke session
                $_SESSION['sukses'] = "Data siswa <b>$nama_siswa</b> berhasil ditambahkan!";
                mysqli_stmt_close($stmt);
                mysqli_close($koneksi);
                header("Location: lihat_siswa.php");
                exit();
            } else {
                $error[] = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($cek_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - Website Input Nilai Siswa</title>

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

            <!-- Judul -->
            <div class="d-flex align-items-center mb-4 animate__animated animate__fadeInDown">
                <a href="lihat_siswa.php" class="btn btn-light me-3 shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="text-white fw-bold mb-0">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Siswa
                </h4>
            </div>

            <!-- Card Form -->
            <div class="card-custom animate__animated animate__fadeInUp">
                <form method="POST" action="tambah_siswa.php">
                    <div class="row g-3">
                        <!-- NIS -->
                        <div class="col-12 col-md-6">
                            <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nis"
                                id="nis"
                                class="form-control"
                                placeholder="Masukkan NIS"
                                value="<?= isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : '' ?>"
                                required
                            >
                        </div>

                        <!-- Nama Siswa -->
                        <div class="col-12 col-md-6">
                            <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="nama_siswa"
                                id="nama_siswa"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                                value="<?= isset($_POST['nama_siswa']) ? htmlspecialchars($_POST['nama_siswa']) : '' ?>"
                                required
                            >
                        </div>

                        <!-- Kelas -->
                        <div class="col-12 col-md-6">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" id="kelas" class="form-select" required>
                                <option value="" disabled <?= !isset($_POST['kelas']) ? 'selected' : '' ?>>-- Pilih Kelas --</option>
                                <?php
                                $daftar_kelas = ['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XII RPL 1', 'XII RPL 2'];
                                foreach ($daftar_kelas as $kls):
                                    $selected = (isset($_POST['kelas']) && $_POST['kelas'] === $kls) ? 'selected' : '';
                                ?>
                                    <option value="<?= $kls ?>" <?= $selected ?>><?= $kls ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-12 col-md-6">
                            <label for="mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mata_pelajaran" id="mata_pelajaran" class="form-select" required>
                                <option value="" disabled <?= !isset($_POST['mata_pelajaran']) ? 'selected' : '' ?>>-- Pilih Mata Pelajaran --</option>
                                <?php
                                $daftar_mapel = ['Pemrograman Web', 'Basis Data', 'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pemrograman Dasar'];
                                foreach ($daftar_mapel as $mapel):
                                    $selected = (isset($_POST['mata_pelajaran']) && $_POST['mata_pelajaran'] === $mapel) ? 'selected' : '';
                                ?>
                                    <option value="<?= $mapel ?>" <?= $selected ?>><?= $mapel ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nilai -->
                        <div class="col-12 col-md-6">
                            <label for="nilai" class="form-label">Nilai <span class="text-danger">*</span> <small class="text-muted">(0 - 100)</small></label>
                            <input
                                type="number"
                                name="nilai"
                                id="nilai"
                                class="form-control"
                                placeholder="Masukkan nilai"
                                min="0"
                                max="100"
                                step="0.01"
                                value="<?= isset($_POST['nilai']) ? htmlspecialchars($_POST['nilai']) : '' ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" name="simpan" class="btn btn-simpan">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                        <a href="lihat_siswa.php" class="btn btn-outline-secondary btn-batal">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script -->
    <script>
        function updateJam() {
            const now = new Date();
            const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const jamEl = document.getElementById('jam');
            if (jamEl) jamEl.textContent = '🕐 ' + jam;
        }
        setInterval(updateJam, 1000);
        updateJam();

        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }
    </script>

    <!-- SweetAlert untuk error validasi -->
    <?php if (!empty($error)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: '<?= implode('<br>', $error) ?>',
            confirmButtonColor: '#667eea'
        });
    </script>
    <?php endif; ?>

</body>
</html>