$host = 'aws-0-ap-south-1.pooler.supabase.com';
$port = '6543';
$db   = 'postgres';
$user = 'postgres.gqkjqqrvxfmldrodttjg';
$pass = 'postgresql://postgres.gqkjqqrvxfmldrodttjg:tunasaffandi@aws-0-ap-south-1.pooler.supabase.com:6543/postgres';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
    // Berhasil terhubung
} catch (\PDOException $e) {
    echo "Koneksi database gagal: " . $e->getMessage();
}
