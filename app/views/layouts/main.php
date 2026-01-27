<?php

use Core\Auth;
use Helpers\CSRF;

$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= APP_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.4/font/bootstrap-icons.css">
</head>

<body data-bs-theme="light">
  <nav id="sidebar" class="bg-dark text-white border-end">
    <div class="sidebar-header p-3 border-bottom border-secondary">
      <h5 class="mb-0 px-2"><i class="bi bi-cpu-fill me-2"></i> <span>Kendaraan</span></h5>
    </div>
    <div class="nav flex-column py-3">
      <?php if (Auth::check()): ?>
        <a href="/" class="nav-link <?php echo $urlPath === '/' || $urlPath === '/index.php' ? 'active' : ''; ?>">
          <i class="bi bi-speedometer2 fs-5 me-3"></i> <span>Dashboard</span>
        </a>
        <a href="/vehicles" class="nav-link <?php echo $urlPath === '/vehicles' ? 'active' : ''; ?>">
          <i class="bi bi-car-front-fill fs-5 me-3"></i> <span>Kendaraan</span>
        </a>
        <a href="/usage" class="nav-link <?php echo $urlPath === '/usage' ? 'active' : ''; ?>">
          <i class="bi bi-person-square fs-5 me-3"></i> <span>Pengguna Kendaraan</span>
        </a>
        <a href="/maintenance" class="nav-link <?php echo $urlPath === '/maintenance' ? 'active' : ''; ?>">
          <i class="bi bi-tools me-2 fs-5 me-3"></i> <span>Pemeliharaan</span>
        </a>
        <a href="/tax" class="nav-link <?php echo $urlPath === '/tax' ? 'active' : ''; ?>">
          <i class="bi bi-file-earmark-text fs-5 me-3"></i> <span>Pajak</span>
        </a>
        <a href="/schedule/maintenance" class="nav-link <?php echo $urlPath === '/schedule/maintenance' ? 'active' : ''; ?>">
          <i class="bi bi-calendar-check fs-5 me-3"></i> <span>Jadwal Pemeliharaan</span>
        </a>
        <a href="/schedule/tax" class="nav-link <?php echo $urlPath === '/schedule/tax' ? 'active' : ''; ?>">
          <i class="bi bi-calendar-event fs-5 me-3"></i> <span>Jadwal Pajak</span>
        </a>

        <?php if (Auth::role() === 'admin'): ?>
          <a href="/users" class="nav-link <?php echo $urlPath === '/users' ? 'active' : ''; ?>">
            <i class="bi bi-people-fill fs-5 me-3"></i> <span>Pengguna</span>
          </a>
          <a href="/import" class="nav-link <?php echo $urlPath === '/import' ? 'active' : ''; ?>">
            <i class="bi bi-arrow-left-right fs-5 me-3"></i> <span>Import/Export</span>
          </a>
          <a href="/backup" class="nav-link <?php echo $urlPath === '/backup' ? 'active' : ''; ?>">
            <i class="bi bi-database-fill-gear fs-5 me-3"></i> <span>Backup & Restore</span>
          </a>
        <?php endif; ?>
        <a href="/logout" class="nav-link text-danger fw-bold">
          <i class="bi bi-box-arrow-right fs-5 me-3"></i> <span>Keluar</span>
        </a>
      <?php endif; ?>
    </div>
  </nav>
  <div id="main-content">
    <nav class="navbar navbar-expand-lg border-bottom bg-body-tertiary sticky-top">
      <div class="container-fluid">
        <button class="btn btn-outline-secondary me-3" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>

        <a class="navbar-brand d-flex align-items-center" href="dashboard">
          <i class="d-none d-sm-inline bi bi-car-front-fill me-2"></i>
          <span class="d-none d-sm-inline">Aset Kendaraan</span>
        </a>

        <div class="ms-auto d-flex align-items-center">
          <button class="btn btn-link nav-link me-3" id="themeToggle">
            <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
          </button>
          <?php
          $display_name = $_SESSION['fullname'] ?? $_SESSION['username'] ?? 'User';
          ?>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($display_name) ?>" alt="mdo" width="32" height="32" class="rounded-circle me-2">
              <span class="d-none d-md-inline text-body"><?= htmlspecialchars($display_name) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid p-4">
      <?php include $viewFile; ?>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    // 1. FUNGSI UNTUK MEMUAT PENGATURAN SAAT HALAMAN DIBUKA
    window.addEventListener('DOMContentLoaded', () => {
      // Muat Status Sidebar
      const sidebarStatus = localStorage.getItem('sidebarStatus');
      if (window.innerWidth > 768 && sidebarStatus === 'collapsed') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
      }

      // Muat Tema (Dark/Light)
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme) {
        body.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);
      }
    });
    // 2. LOGIKA SIDEBAR TOGGLE
    sidebarToggle.addEventListener('click', () => {
      if (window.innerWidth > 768) {
        // Desktop Mode
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');

        // Simpan status ke localStorage
        if (sidebar.classList.contains('collapsed')) {
          localStorage.setItem('sidebarStatus', 'collapsed');
        } else {
          localStorage.setItem('sidebarStatus', 'expanded');
        }
      } else {
        // Mobile Mode
        sidebar.classList.toggle('active');
      }
    });

    // --- Logika Dark Mode dengan Persistence ---

    // Fungsi untuk menerapkan tema
    const setTheme = (theme) => {
      body.setAttribute('data-bs-theme', theme);
      localStorage.setItem('theme', theme); // Simpan pilihan ke browser

      if (theme === 'dark') {
        themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
      } else {
        themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
      }
    };

    // 3. LOGIKA THEME TOGGLE (Juga disimpan agar tetap awet)
    themeToggle.addEventListener('click', () => {
      const currentTheme = body.getAttribute('data-bs-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';

      body.setAttribute('data-bs-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeIcon(newTheme);
    });
    // Fungsi pembantu ganti icon tema
    function updateThemeIcon(theme) {
      if (theme === 'dark') {
        themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
      } else {
        themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
      }
    }
  </script>
</body>

</html>