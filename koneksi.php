<?php
$host = "aws-0-ap-south-1.pooler.supabase.com"; // Sesuaikan host Supabase kamu jika berbeda
$port = "6543";
$dbname = "postgres";
$user = "postgres.gqkjqqrvxfmldrodttjg";
$password = "postgresql://postgres.gqkjqqrvxfmldrodttjg:tunasaffandi@aws-0-ap-south-1.pooler.supabase.com:6543/postgres"; // Ganti dengan password database Supabase kamu

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
