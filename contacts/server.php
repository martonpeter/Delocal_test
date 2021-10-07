<?php

require_once "autoload.php";
require_once "functions.php";

$connector = new ContactsConnector();

print_r($connector->process());
?>