<?php

spl_autoload_register(function ($class) {

    // namespace prefix
    $prefix = '';
    $base_dir = __DIR__ . '/Model/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    // get the relative class name
    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});