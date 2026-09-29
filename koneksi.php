<?php
$host = 'aws-0-ap-south-1.pooler.supabase.com';
$port = '6543';
$db   = 'postgres';
$user = 'postgres.gqkjqqrvxfmldrodttjg';
$pass = 'tunasaffandi'; // Sesuaikan password kamu

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
} catch (\PDOException $e) {
    echo "Koneksi database gagal: " . $e->getMessage();
}
?>
