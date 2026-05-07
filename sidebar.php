<!--
    sidebar.php
    Navigasi sidebar dengan Bootstrap Icon
-->
<nav id="sidebar" class="sidebar">
    <!-- Header Sidebar -->
    <div class="sidebar-header text-center py-3">
        <i class="bi bi-mortarboard-fill" style="font-size: 40px; color: #fff;"></i>
        <h5 class="text-white mt-2 mb-0">Nilai Siswa</h5>
        <small class="text-white-50">Dashboard Admin</small>
    </div>

    <hr class="text-white-50 mx-3">

    <!-- Menu Navigasi -->
    <ul class="nav flex-column px-3">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="lihat_siswa.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'lihat_siswa.php') ? 'active' : '' ?>">
                <i class="bi bi-people-fill me-2"></i> Data Siswa
            </a>
        </li>
        <li class="nav-item">
            <a href="tambah_siswa.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'tambah_siswa.php') ? 'active' : '' ?>">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa
            </a>
        </li>
    </ul>

    <hr class="text-white-50 mx-3 mt-auto">

    <!-- Info User & Logout -->
    <div class="px-3 pb-3">
        <div class="text-white-50 small mb-2">
            <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['nama'] ?>
        </div>
        <a href="logout.php" class="btn btn-outline-light btn-sm w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>
    </div>
</nav>