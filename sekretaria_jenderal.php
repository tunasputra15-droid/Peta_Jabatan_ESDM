<?php
session_start();
include 'koneksi.php';

// Proteksi halaman admin unit
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
    <title>Peta Jabatan - Sekretariat Jenderal - Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;
            --accent-gold-light: rgba(197, 160, 89, 0.15);
            --bg-body: #E0F2FE;
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

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #FFFFFF;
            color: var(--primary-esdm);
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.04);
            border-right: 1px solid #E2E8F0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #E2E8F0;
            color: var(--primary-esdm);
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
            color: #64748B;
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

        .sidebar ul li a:hover {
            background-color: #F1F5F9;
            color: var(--primary-esdm);
        }

        .sidebar ul li a.active {
            background-color: var(--accent-gold-light);
            color: var(--primary-esdm);
            font-weight: 600;
            border-left: 4px solid var(--accent-gold);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Header Kuning Khas Kementerian ESDM */
        .esdm-header-bar {
            background: #FACC15; /* Warna kuning khas ESDM */
            color: #0A192F;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .esdm-header-bar .badge-ad {
            background: #0A192F;
            color: #FACC15;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .back-link {
            font-size: 13px;
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
        }

        .page-main-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-esdm);
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 15px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #FACC15;
        }

        /* Card Baris Rekapitulasi */
        .recap-card {
            background: white;
            border: 2px solid #FACC15;
            border-radius: 10px;
            padding: 18px 25px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: 50px 1.5fr 1fr 1fr 1fr;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        @media (max-width: 900px) {
            .recap-card {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        .recap-icon {
            width: 38px;
            height: 38px;
            background: #FACC15;
            color: var(--primary-esdm);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .recap-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-esdm);
            text-transform: uppercase;
        }

        .recap-col {
            text-align: center;
        }

        .recap-col .label {
            font-size: 10px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .recap-col .value-blue {
            font-size: 18px;
            font-weight: 800;
            color: #2563EB;
        }

        .recap-col .value-green {
            font-size: 18px;
            font-weight: 800;
            color: #059669;
        }

        .recap-col .value-red {
            font-size: 18px;
            font-weight: 800;
            color: #DC2626;
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
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="has-submenu">
                <a href="kelola_peta_jabatan.php" class="active">
                    <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan 
                    <i class="fa-solid fa-chevron-up" style="margin-left: auto; font-size: 11px;"></i>
                </a>
                <ul class="submenu" style="list-style: none; padding-left: 20px; margin-top: 5px;">
                    <li><a href="sekretariat_jenderal.php" style="font-size: 13px; padding: 8px 12px; color: var(--accent-gold); font-weight: 600;">1. Sekretariat Jenderal</a></li>
                    <li><a href="ditjen_migas.php" style="font-size: 13px; padding: 8px 12px; color: #64748B;">2. Ditjen Migas</a></li>
                </ul>
            </li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Content Utama -->
    <div class="content">
        
        <!-- Header Kuning Kementerian ESDM -->
        <div class="esdm-header-bar">
            <span>Kementerian ESDM &nbsp;&nbsp;&gt;&nbsp;&nbsp; 1. Sekretariat Jenderal</span>
            <div class="badge-ad">AD</div>
        </div>

        <a href="kelola_peta_jabatan.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pusat</a>
        
        <div class="page-main-title">PETA JABATAN</div>
        <div class="page-subtitle">1. Sekretariat Jenderal</div>

        <!-- Daftar Baris Rekapitulasi Sesuai Gambar 2 -->
        <div class="recap-card">
            <div class="recap-icon"><i class="fa-solid fa-users"></i></div>
            <div class="recap-title">Total Pegawai</div>
            <div class="recap-col">
                <div class="label">Kebutuhan</div>
                <div class="value-blue">820</div>
            </div>
            <div class="recap-col">
                <div class="label">Eksisting</div>
                <div class="value-green">765</div>
            </div>
            <div class="recap-col">
                <div class="label">Selisih</div>
                <div class="value-red">-55</div>
            </div>
        </div>

        <div class="recap-card">
            <div class="recap-icon"><i class="fa-solid fa-user-tie"></i></div>
            <div class="recap-title">Jabatan Struktural</div>
            <div class="recap-col">
                <div class="label">Kebutuhan</div>
                <div class="value-blue">41</div>
            </div>
            <div class="recap-col">
                <div class="label">Eksisting</div>
                <div class="value-green">40</div>
            </div>
            <div class="recap-col">
                <div class="label">Selisih</div>
                <div class="value-red">-1</div>
            </div>
        </div>

        <div class="recap-card">
            <div class="recap-icon"><i class="fa-solid fa-user-gear"></i></div>
            <div class="recap-title">Jab. Admin / Pengawas</div>
            <div class="recap-col">
                <div class="label">Kebutuhan</div>
                <div class="value-blue">82</div>
            </div>
            <div class="recap-col">
                <div class="label">Eksisting</div>
                <div class="value-green">74</div>
            </div>
            <div class="recap-col">
                <div class="label">Selisih</div>
                <div class="value-red">-8</div>
            </div>
        </div>

    </div>

</body>
</html>
