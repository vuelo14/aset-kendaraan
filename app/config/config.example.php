<?php
// Konfigurasi aplikasi template

$autoloadPath = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($value === false || $value === null) {
            return $default;
        }

        switch (strtolower(trim((string)$value))) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

$rootDir = dirname(__DIR__, 2);
if (class_exists('Dotenv\Dotenv') && file_exists($rootDir . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($rootDir);
    $dotenv->safeLoad();
} elseif (file_exists($rootDir . '/.env')) {
    $lines = file($rootDir . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
            $value = $matches[2];
        }
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

define('BASE_URL', env('APP_URL') ?: env('BASE_URL', 'http://localhost:8080'));
define('APP_PATH', dirname(__DIR__));

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_NAME') ?: env('DB_DATABASE', 'kendaraan_disnaker'));
define('DB_USER', env('DB_USER') ?: env('DB_USERNAME', 'root'));
define('DB_PASS', env('DB_PASS') !== null ? (string)env('DB_PASS') : (string)env('DB_PASSWORD', ''));

define('UPLOAD_DIR', dirname(__DIR__, 2) . '/public/assets/uploads/vehicles');
define('APP_NAME', env('APP_NAME', 'Manajemen Aset Kendaraan Disnaker Indramayu'));
define('APP_VERSION', env('APP_VERSION', '1.2.0'));

// Google Gemini AI Configuration
define('GEMINI_API_KEY', env('GEMINI_API_KEY', ''));
define('GEMINI_MODEL', env('GEMINI_MODEL', 'gemini-2.5-flash'));
define('GEMINI_FALLBACK_MODELS', env('GEMINI_FALLBACK_MODELS', 'gemini-2.0-flash,gemini-1.5-flash'));

