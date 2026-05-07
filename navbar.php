<!--
    navbar.php
    Header dengan tombol toggle sidebar
-->
<nav class="navbar-top">
    <div class="d-flex align-items-center">
        <!-- Tombol Toggle Sidebar (Mobile) -->
        <button id="sidebarToggle" class="btn btn-link text-white me-3 d-md-none">
            <i class="bi bi-list" style="font-size: 24px;"></i>
        </button>

        <!-- Judul Halaman -->
        <h5 class="mb-0 text-white">
            <?php
                // Tentukan judul berdasarkan nama file
                $halaman = basename($_SERVER['PHP_SELF']);
                switch ($halaman) {
                    case 'dashboard.php':   echo '📊 Dashboard'; break;
                    case 'lihat_siswa.php': echo '👥 Data Siswa'; break;
                    case 'tambah_siswa.php': echo '➕ Tambah Siswa'; break;
                    case 'edit_siswa.php':  echo '✏️ Edit Siswa'; break;
                    default:                echo '📄 Halaman'; break;
                }
            ?>
        </h5>
    </div>

    <!-- Info User & Jam -->
    <div class="d-flex align-items-center text-white">
        <span class="me-3 d-none d-md-inline" id="jam"></span>
        <div class="dropdown">
            <button class="btn btn-link text-white dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['nama'] ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted small"><?= $_SESSION['username'] ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>