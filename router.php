<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
} 
// Jika mengakses dashboard
else if ($uri === '/dashboard.php' || $uri === '/Dashboard.php') {
    if (file_exists(__DIR__ . '/dashboard.php')) {
        include 'dashboard.php';
    } else {
        echo "File dashboard.php tidak ditemukan!";
    }
} 
// Jika mengakses halaman kelola peta jabatan
else if ($uri === '/kelola_peta.php') {
    if (file_exists(__DIR__ . '/kelola_peta.php')) {
        include 'kelola_peta.php';
    } else {
        echo "File kelola_peta.php tidak ditemukan!";
    }
} 
else {
    if (file_exists(__DIR__ . '/login.php')) {
        include 'login.php';
    } else {
        echo "File login.php tidak ditemukan!";
    }
}
