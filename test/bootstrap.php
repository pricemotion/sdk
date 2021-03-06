<?php
namespace Pricemotion\Sdk;

spl_autoload_register(function ($class) {
    if (substr($class, 0, strlen(__NAMESPACE__) + 1) !== __NAMESPACE__ . '\\') {
        return;
    }
    $relative_class = substr($class, strlen(__NAMESPACE__) + 1);
    $path = __DIR__ . '/../' . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
