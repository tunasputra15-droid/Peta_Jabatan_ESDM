<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Cek apakah user sudah login dan role-nya admin_unit
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin_unit') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Unit - Peta Jabatan Kementerian ESDM</title>
    <!-- Memuat Google Fonts & FontAwesome untuk ikon modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F; /* Biru Tua Formal Kementerian */
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;   /* Aksen Emas Kementerian ESDM */
            --bg-body: #F4F7FC;
            --text-main: #334155;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* Sidebar Navigation */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-esdm) 0%, var(--secondary-esdm) 100%);
            color: white;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .sidebar-brand i {
            color: var(--accent-gold);
            font-size: 22px;
        }

        .sidebar ul {
            list-style: none;
            flex: 1;
        }

        .sidebar ul li {
            margin-bottom: 8px;
        }

        .sidebar ul li a {
            color: #94A3B8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar ul li a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: rgba(197, 160, 89, 0.15);
            color: #ffffff;
            border-left: 4px solid var(--accent-gold);
        }

        /* Main Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid var(--accent-gold);
        }

        .header h2 {
            font-size: 22px;
            font-weight: 600;
            color: var(--primary-esdm);
        }

        .header h2 span {
            color: var(--accent-gold);
        }

        .btn-logout {
            background-color: #EF4444;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background-color: #DC2626;
        }

        /* Card Statistics Grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border-top: 4px solid var(--primary-esdm);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.06);
        }

        .card:nth-child(2) {
            border-top-color: var(--accent-gold);
        }

        .card:nth-child(3) {
            border-top-color: #10B981;
        }

        .card h4 {
            color: #64748B;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card h4 i {
            font-size: 16px;
            color: #94A3B8;
        }

        .card .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-esdm);
        }

        /* Info Panel */
        .info-panel {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .info-panel h3 {
            font-size: 18px;
            color: var(--primary-esdm);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-panel h3 i {
            color: var(--accent-gold);
        }

        .info-panel p {
            color: #64748B;
            line-height: 1.6;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                padding: 15px;
            }
            .content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-building-shield"></i>
            <span>ADMIN UNIT ESDM</span>
        </div>
        <ul>
            <li><a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan</a></li>
            <li><a href="statistik.php"><i class="fa-solid fa-chart-column"></i> Statistik Ringkasan</a></li>
            <li><a href="#"><i class="fa-solid fa-pen-to-square"></i> Usulan Tambah/Edit</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="header">
            <h2>Selamat Datang, <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>!</h2>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="card-grid">
            <div class="card">
                <h4>Total Jabatan <i class="fa-solid fa-briefcase"></i></h4>
                <div class="number">0</div>
            </div>
            <div class="card">
                <h4>Status Pengajuan <i class="fa-solid fa-clock-rotate-left"></i></h4>
                <div class="number" style="font-size: 22px; color: var(--accent-gold);">Draft</div>
            </div>
            <div class="card">
                <h4>Hasil Verifikasi <i class="fa-solid fa-circle-check"></i></h4>
                <div class="number">-</div>
            </div>
        </div>

        <!-- Panel Informasi / Operasional -->
        <div class="info-panel">
            <h3><i class="fa-solid fa-circle-info"></i> Panel Operasional Admin Unit</h3>
            <p>
                Halaman ini digunakan oleh Admin Unit Kementerian ESDM untuk menginput, memperbarui, serta mengajukan draf peta jabatan kepada Validator Pusat secara terintegrasi dan transparan.
            </p>
        </div>
    </div>

</body>
</html>
