<?php
/**
 * edit_siswa.php
 * Form edit data siswa
 */

// Cek session
require_once 'session_check.php';

// Include koneksi
require_once 'koneksi.php';

// Cek parameter id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: lihat_siswa.php");
    exit();
}

$id_siswa = (int) $_GET['id'];

// Ambil data siswa berdasarkan id
$query = "SELECT * FROM siswa WHERE id_siswa = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Jika data tidak ditemukan
if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
    header("Location: lihat_siswa.php");
    exit();
}

$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Proses update data
if (isset($_POST['update'])) {
    // Ambil & bersihkan input
    $nis             = bersihkan_input($_POST['nis']);
    $nama_siswa      = bersihkan_input($_POST['nama_siswa']);
    $kelas           = bersihkan_input($_POST['kelas']);
    $mata_pelajaran  = bersihkan_input($_POST['mata_pelajaran']);
    $nilai           = $_POST['nilai'];

    // Validasi
    $error = [];
    if (empty($nis))            $error[] = "NIS wajib diisi!";
    if (empty($nama_siswa))     $error[] = "Nama Siswa wajib diisi!";
    if (empty($kelas))          $error[] = "Kelas wajib diisi!";
    if (empty($mata_pelajaran)) $error[] = "Mata Pelajaran wajib diisi!";
    if ($nilai === '')          $error[] = "Nilai wajib diisi!";

    if ($nilai !== '' && ($nilai < 0 || $nilai > 100)) {
        $error[] = "Nilai harus antara 0 - 100!";
    }

    // Cek NIS duplikat (kecuali milik sendiri)
    if (empty($error)) {
        $cek_query = "SELECT id_siswa FROM siswa WHERE nis = ? AND id_siswa != ?";
        $cek_stmt = mysqli_prepare($koneksi, $cek_query);
        mysqli_stmt_bind_param($cek_stmt, "si", $nis, $id_siswa);
        mysqli_stmt_execute($cek_stmt);
        mysqli_stmt_store_result($cek_stmt);

        if (mysqli_stmt_num_rows($cek_stmt) > 0) {
            $error[] = "NIS <b>$nis</b> sudah digunakan siswa lain!";
        }
        mysqli_stmt_close($cek_stmt);
    }

    // Update jika tidak error
    if (empty($error)) {
        $update_query = "UPDATE siswa SET nis = ?, nama_siswa = ?, kelas = ?, mata_pelajaran = ?, nilai = ? WHERE id_siswa = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssssdi", $nis, $nama_siswa, $kelas, $mata_pelajaran, $nilai, $id_siswa);

        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['sukses'] = "Data siswa <b>$nama_siswa</b> berhasil diupdate!";
            mysqli_stmt_close($update_stmt);
            mysqli_close($koneksi);
            header("Location: lihat_siswa.php");
            exit();
        } else {
            $error[] = "Gagal mengupdate data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($update_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa - Website Input Nilai Siswa</title>

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
                    <i class="bi bi-pencil-square me-2"></i>Edit Siswa
                </h4>
            </div>

            <!-- Card Form -->
            <div class="card-custom animate__animated animate__fadeInUp">
                <form method="POST" action="edit_siswa.php?id=<?= $id_siswa ?>">
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
                                value="<?= isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : htmlspecialchars($data['nis']) ?>"
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
                                value="<?= isset($_POST['nama_siswa']) ? htmlspecialchars($_POST['nama_siswa']) : htmlspecialchars($data['nama_siswa']) ?>"
                                required
                            >
                        </div>

                        <!-- Kelas -->
                        <div class="col-12 col-md-6">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" id="kelas" class="form-select" required>
                                <option value="" disabled>-- Pilih Kelas --</option>
                                <?php
                                $kelas_sekarang = isset($_POST['kelas']) ? $_POST['kelas'] : $data['kelas'];
                                $daftar_kelas = ['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XII RPL 1', 'XII RPL 2'];
                                foreach ($daftar_kelas as $kls):
                                    $selected = ($kelas_sekarang === $kls) ? 'selected' : '';
                                ?>
                                    <option value="<?= $kls ?>" <?= $selected ?>><?= $kls ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-12 col-md-6">
                            <label for="mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mata_pelajaran" id="mata_pelajaran" class="form-select" required>
                                <option value="" disabled>-- Pilih Mata Pelajaran --</option>
                                <?php
                                $mapel_sekarang = isset($_POST['mata_pelajaran']) ? $_POST['mata_pelajaran'] : $data['mata_pelajaran'];
                                $daftar_mapel = ['Pemrograman Web', 'Basis Data', 'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pemrograman Dasar'];
                                foreach ($daftar_mapel as $mapel):
                                    $selected = ($mapel_sekarang === $mapel) ? 'selected' : '';
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
                                value="<?= isset($_POST['nilai']) ? htmlspecialchars($_POST['nilai']) : $data['nilai'] ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" name="update" class="btn btn-update">
                            <i class="bi bi-check-circle me-1"></i> Update Data
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
<?php mysqli_close($koneksi); ?>