<?php
// index.php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Direct the router to your files inside the 'api/' folder
if ($path == '/login') {
    require 'api/login.php';
} elseif ($path == '/dashboard') {
    require 'api/dashboard.php';
} elseif ($path == '/profiles') {
    require 'api/profiles.php';
} elseif ($path == '/search') {
    require 'api/search.php'; // <--- ADDED THIS
} else {
    // Default to login if no path matches
    require 'api/login.php';
}