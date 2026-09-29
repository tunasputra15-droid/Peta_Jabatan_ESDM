<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
} 
else if ($uri === '/dashboard.php' || $uri === '/Dashboard.php') {
    if (file_exists(__DIR__ . '/dashboard.php')) {
        include 'dashboard.php';
    } else {
        echo "File dashboard.php tidak ditemukan!";
    }
} 
else if ($uri === '/kelola_peta_jabatan.php') {
    if (file_exists(__DIR__ . '/kelola_peta_jabatan.php')) {
        include 'kelola_peta_jabatan.php';
    } else {
        echo "File kelola_peta_jabatan.php tidak ditemukan!";
    }
} 
else {
    if (file_exists(__DIR__ . '/login.php')) {
        include 'login.php';
    } else {
        echo "File login.php tidak ditemukan!";
    }
}
