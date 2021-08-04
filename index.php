<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use src\App;

require_once realpath("vendor/autoload.php");

$app = new App();
try {
    $app->init();
} catch (Exception $e) {

}

