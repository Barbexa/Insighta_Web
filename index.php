<?
// index.php (The Router)
$path = $_SERVER['REQUEST_URI'];

if ($path == '/login') {
    include 'login.php';
} elseif ($path == '/dashboard') {
    include 'dashboard.php';
} else {
    echo "404 Not Found";
}
?>