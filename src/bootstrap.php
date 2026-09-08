<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

$configFile = ROOT_PATH . '/config.php';
if (!is_file($configFile)) {
    $configFile = ROOT_PATH . '/config.example.php';
}

$GLOBALS['config'] = require $configFile;

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = ROOT_PATH . '/src/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require ROOT_PATH . '/src/helpers.php';
