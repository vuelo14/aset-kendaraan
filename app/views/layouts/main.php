<?php
use Core\Auth; use Helpers\CSRF;
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= APP_NAME ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>
<div class="d-flex">
  <nav class="flex-shrink-0 p-3 bg-dark text-white" style="width:260px; min-height:100vh;">
    <a href="/" class="d-flex align-items-center mb-3 me-md-auto text-white text-decoration-none">
      <span class="fs-5 fw-bold">Kendaraan Disnaker</span>
    </a>
    <hr>
    <?php if(Auth::check()): ?>
    <ul class="nav nav-pills flex-column mb-auto gap-1">
      <li class="nav-item"><a href="/" class="nav-link text-white">Dashboard</a></li>
      <li><a href="/vehicles" class="nav-link text-white">Kendaraan</a></li>
      <li><a href="/maintenance" class="nav-link text-white">Pemeliharaan</a></li>
      <li><a href="/tax" class="nav-link text-white">Pajak</a></li>
      <li><a href="/schedule/maintenance" class="nav-link text-white">Jadwal Pemeliharaan</a></li>
      <li><a href="/schedule/tax" class="nav-link text-white">Jadwal Pajak</a></li>
      <?php if(Auth::role()==='admin'): ?>
        <li><a href="/users" class="nav-link text-white">Pengguna</a></li>
        <li><a href="/import" class="nav-link text-white">Import/Export</a></li>
        <li><a href="/backup" class="nav-link text-white">Backup & Restore</a></li>
      <?php endif; ?>
      <li><a href="/logout" class="nav-link text-white">Keluar</a></li>
    </ul>
    <?php endif; ?>
  </nav>
  <main class="flex-grow-1">
    <div class="p-4">
      <?php include $viewFile; ?>
    </div>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</body>
</html>
