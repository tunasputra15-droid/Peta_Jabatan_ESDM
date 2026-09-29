<?php
// Mengambil URL yang sedang diakses
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Jika file fisik yang diminta benar-benar ada (misal file .css, .js, .png, atau .php spesifik), biarkan dimuat
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
} 

// Jika mengakses halaman dashboard
else if ($uri === '/dashboard.php') {
    if (file_exists(__DIR__ . '/dashboard.php')) {
        include 'dashboard.php';
    } else {
        echo "File dashboard.php tidak ditemukan di direktori utama!";
    }
} 

// Jika mengakses halaman utama atau proses login
else {
    if (file_exists(__DIR__ . '/login.php')) {
        include 'login.php';
    } else {
        echo "File login.php tidak ditemukan!";
    }
}
