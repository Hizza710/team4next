<?php
// app/bootstrap.php
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/';
    $directories = [
        $base_dir,
        $base_dir . 'Controllers/', // ここが重要
        $base_dir . 'Services/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
// ヘルパー関数の読み込みなど
require_once __DIR__ . '/Db.php';
