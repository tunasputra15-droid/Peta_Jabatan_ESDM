<?php
$host = "db.gqkjqqrvxfmldrodttjg.supabase.co"; // Sesuaikan host Supabase kamu jika berbeda
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "postgresql://postgres:tunasaffandi@db.gqkjqqrvxfmldrodttjg.supabase.co:5432/postgres"; // Ganti dengan password database Supabase kamu

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
