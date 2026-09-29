<?php
session_start();
include '../koneksi.php';

// Proteksi halaman: Cek apakah user sudah login dan role-nya admin_unit
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin_unit') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Unit - Peta Jabatan ESDM</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f6f9;
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar Navigation */
        .sidebar {
            width: 250px;
            background-color: #1b365d;
            color: white;
            padding: 20px;
        }
        .sidebar h3 {
            font-size: 16px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #2c4d75;
        }
        .sidebar ul {
            list-style: none;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            color: #d1d8e0;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #2c4d75;
            color: white;
        }
        /* Main Content */
        .content {
            flex: 1;
            padding: 30px;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-left: 4px solid #1b365d;
        }
        .card h4 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .card .number {
            font-size: 24px;
            font-weight: bold;
            color: #1b365d;
        }
        .btn-logout {
            background-color: #d9534f;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }
        .btn-logout:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>ADMIN UNIT ESDM</h3>
        <ul>
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="#">Kelola Peta Jabatan</a></li>
            <li><a href="#">Usulan Tambah/Edit</a></li>
            <li><a href="../logout.php">Keluar</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="header">
            <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="card-grid">
            <div class="card">
                <h4>Total Jabatan</h4>
                <div class="number">0</div>
            </div>
            <div class="card">
                <h4>Status Pengajuan</h4>
                <div class="number">Draft</div>
            </div>
            <div class="card">
                <h4>Hasil Verifikasi</h4>
                <div class="number">-</div>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px;">
            <h3>Panel Operasional Admin Unit</h3>
            <p style="margin-top: 10px; color: #666;">
                Halaman ini digunakan oleh Admin Unit untuk menginput, memperbarui, dan mengajukan draf peta jabatan ke Validator Pusat.
            </p>
        </div>
    </div>

</body>
</html>
