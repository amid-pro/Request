<?php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

spl_autoload_register(function ($className) {

    $class = ROOT . 'App' . DIRECTORY_SEPARATOR . $className . '.php';

    if (file_exists($class)) {
        require_once $class;
    }
});

Config::set();

set_time_limit(getenv("TIME_LIMIT") ?: 0);

(new Controller())->run();
