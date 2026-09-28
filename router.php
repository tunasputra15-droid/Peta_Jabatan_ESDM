<?php
// Jika mengakses root, arahkan langsung ke halaman login
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
} else {
    include 'login.php'; // Ganti dengan file utama/login kamu jika berbeda
}
