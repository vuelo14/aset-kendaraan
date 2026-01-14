<?php
spl_autoload_register(function ($class) {
    $prefixes = [
        'Core' => __DIR__,
        'Controllers' => __DIR__ . '/../controllers',
        'Models' => __DIR__ . '/../models',
        'Helpers' => __DIR__ . '/../helpers'
    ];
    foreach ($prefixes as $prefix => $dir) {
        $prefixLen = strlen($prefix);
        if (strncmp($class, $prefix, $prefixLen) === 0) {
            $relative = substr($class, $prefixLen + 1);
            $file = $dir . '/' . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});
