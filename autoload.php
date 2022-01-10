<?php declare(strict_types=1);

namespace Pricemotion\Sdk;

spl_autoload_register(function (string $class): void {
    if (strpos($class, __NAMESPACE__ . '\\') !== 0) {
        return;
    }
    $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, strlen(__NAMESPACE__) + 1)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
