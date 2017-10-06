<?php
spl_autoload_register(function($class) {
    $file = __DIR__.'/' . $class . '.php';
    if (is_readable($file)) {
    require_once $file;
    }
});