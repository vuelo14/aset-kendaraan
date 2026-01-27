<?php
// Konfigurasi aplikasi

define('BASE_URL', '');
define('APP_PATH', dirname(__DIR__));

define('DB_HOST', 'db');
define('DB_NAME', 'web_aset_kendaraan');
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASSWORD'));

define('UPLOAD_DIR', __DIR__ . '/../../assets/uploads/vehicles');

define('APP_NAME', 'Manajemen Aset Kendaraan Disnaker Indramayu');
