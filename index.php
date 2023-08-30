<?php
declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('exceptions_error_handler', '1');
error_reporting(E_ALL);
include_once(__DIR__ . '/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
include_once(__DIR__ . '/routes/routes.php');
