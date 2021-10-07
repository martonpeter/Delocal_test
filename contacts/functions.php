<?php

/**
 * Ide jönnek a segédfüggvények
 */

/**
 * 
 * @param type $string
 */
function esc($string) {
    echo htmlspecialchars($string);
}

/**
 * A config.php-ban lévő $config tömb
 * megfelelő elemét adja vissza.
 * 
 * @param type string
 * @return type $key string or Array
 */
function getConfig($key) {

    require "config.php";
    return $config[$key];
}

/**
 * Logolást segíti elő a backendTest.log fájlba.
 * 
 * @param type $level string
 * @param type $message string
 */
function logMessage($level, $message) {
    $file = fopen('backendTest.log', "a");
    fwrite($file, (new DateTime())->format('Y-m-d H:i:s') . " [$level]: $message" . PHP_EOL);
    fclose($file);
}
