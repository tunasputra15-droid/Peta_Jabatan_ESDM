<?php
$host = 'aws-0-ap-south-1.pooler.supabase.com';
$port = '6543';
$db   = 'postgres';
$user = 'postgres.gqkjqqrvxfmldrodttjg';
$pass = 'tunasaffandi'; // Sesuaikan password kamu

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    // Ubah dari $pdo menjadi $conn agar sesuai dengan login.php
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo "Koneksi database gagal: " . $e->getMessage();
}
?>
