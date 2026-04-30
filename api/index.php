<?php
// index.php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path == '/login') {
    require 'login.php';
} elseif ($path == '/dashboard') {
    require 'dashboard.php';
} elseif ($path == '/profiles') {
    require 'profiles.php';
} else {
    // Default or 404
    require 'login.php';
}