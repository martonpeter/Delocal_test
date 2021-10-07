<?php

/**
 * Nem akartam külső könyvtárat használni a tesztfeladat végett,
 * ezért készítettem saját autoloadert, egyébként composer segitségével telepítenék.
 * 
 */
function databasesAutoload($className) {
    $classWithPath = strtr(__DIR__ . "/databases/" . $className . ".php",
            "/\\",
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
    );
    if (file_exists($classWithPath)) {
        include_once $classWithPath;
    }
}

spl_autoload_register('databasesAutoload');
