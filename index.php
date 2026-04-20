<?php
session_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/storage/logs/app.log');

spl_autoload_register(function ($class) {
    if (strpos($class, 'EkaCore\\') === 0) {
        $file = __DIR__ . '/core/' . str_replace('EkaCore\\', '', $class) . '.php';
    } elseif (strpos($class, 'EkaApp\\') === 0) {
        $file = __DIR__ . '/app/' . str_replace(['EkaApp\\', '\\'], ['', '/'], $class) . '.php';
    }
    if (isset($file) && file_exists($file)) {
        require_once $file;
    }
});

if (!file_exists(__DIR__ . '/storage/logs/app.log')) {
    if (!is_dir(__DIR__ . '/storage/logs')) {
        mkdir(__DIR__ . '/storage/logs', 0777, true);
    }
    touch(__DIR__ . '/storage/logs/app.log');
}

EkaCore\EkaLogger::init(__DIR__ . '/storage/logs/app.log');
EkaCore\EkaDatabase::init(require __DIR__ . '/config/database.php');

$router = new EkaCore\EkaRouter();
require_once __DIR__ . '/routes/web.php';

try {
    $router->dispatch();
} catch (Exception $e) {
    EkaCore\EkaLogger::error($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    require __DIR__ . '/app/Views/errors/500.php';
}
