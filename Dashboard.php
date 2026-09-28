<?php
session_start();

// Validasi: Pastikan user sudah login dan rolenya benar-benar validator
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'validator') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Validator Pusat - Peta Jabatan ESDM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background-color: #f4f6f9; display: flex; height: 100vh; }
        .sidebar { width: 250px; background-color: #1f3b64; color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; }
        .sidebar h2 { font-size: 18px; margin-bottom: 30px; text-align: center; }
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 10px 15px; border-radius: 4px; margin-bottom: 5px; display: block; }
        .sidebar a:hover { background-color: #2c4d7d; color: white; }
        .logout-btn { background-color: #d9534f; color: white !important; text-align: center; }
        .logout-btn:hover { background-color: #c9302c !important; }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .card h1 { color: #1f3b64; margin-bottom: 10px; font-size: 22px; }
        .card p { color: #555; font-size: 14px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <h2>VALIDATOR PUSAT</h2>
            <a href="dashboard.php">Dashboard</a>
            <a href="#">Verifikasi Draf Unit</a>
        </div>
        <div>
            <a href="../logout.php" class="logout-btn">Keluar (Logout)</a>
        </div>
    </div>

    <div class="main-content">
        <div class="card">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
            <p>Anda berhasil masuk sebagai <strong>Validator Pusat</strong>. Dari halaman ini, Anda dapat memeriksa, memvalidasi, atau merevisi draf Peta Jabatan yang diajukan oleh unit-unit kerja.</p>
        </div>
    </div>

</body>
</html>
