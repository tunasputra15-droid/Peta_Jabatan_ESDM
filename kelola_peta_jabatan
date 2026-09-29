<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Pastikan sudah login dan rolenya sesuai
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
    <title>Kelola Peta Jabatan - Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;
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

        /* Sidebar */
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

        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: rgba(197, 160, 89, 0.15);
            color: #ffffff;
            border-left: 4px solid var(--accent-gold);
        }

        /* Content */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Top Banner */
        .top-banner {
            background-color: var(--primary-esdm);
            color: white;
            padding: 20px 25px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .top-banner h1 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .admin-badge {
            background: rgba(197, 160, 89, 0.2);
            border: 1px solid var(--accent-gold);
            color: var(--accent-gold);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Action Toolbar */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 350px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            background-color: var(--primary-esdm);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-action:hover {
            background-color: var(--secondary-esdm);
        }

        /* Structural Org Chart Mockup */
        .org-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .box-direktur {
            background: white;
            border: 2px solid var(--accent-gold);
            border-radius: 12px;
            padding: 20px 30px;
            text-align: center;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .box-direktur .role-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--accent-gold);
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .box-direktur .role-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--primary-esdm);
            margin-bottom: 12px;
        }

        .counter-badge {
            display: inline-block;
            background: #E2E8F0;
            color: #1E293B;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .connector-line {
            width: 2px;
            height: 35px;
            background-color: #94A3B8;
        }

        .sub-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .sub-grid {
                grid-template-columns: 1fr;
            }
        }

        .sub-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .sub-header {
            background: var(--primary-esdm);
            color: white;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .sub-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .position-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .position-item:hover {
            background: #EDF2F7;
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
            <li><a href="kelola_peta_jabatan.php" class="active"><i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan</a></li>
            <li><a href="#"><i class="fa-solid fa-pen-to-square"></i> Usulan Tambah/Edit</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <!-- Top Banner -->
        <div class="top-banner">
            <h1>PETA JABATAN DIREKTORAT PEMBINAAN USAHA HULU MINYAK DAN GAS BUMI</h1>
            <div class="admin-badge">
                <i class="fa-solid fa-shield-halved"></i> Admin Panel
            </div>
        </div>

        <!-- Toolbar Pencarian & Ekspor -->
        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari jabatan...">
            </div>
            <div class="action-buttons">
                <button class="btn-action"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
                <button class="btn-action"><i class="fa-solid fa-code"></i> Export JSON</button>
                <button class="btn-action"><i class="fa-solid fa-upload"></i> Import JSON</button>
            </div>
        </div>

        <!-- Struktur Peta Jabatan -->
        <div class="org-container">
            <div class="box-direktur">
                <div class="role-title">Direktur</div>
                <div class="role-name">Pembinaan Usaha Hulu Minyak dan Gas Bumi</div>
                <div class="counter-badge">15 - 0 - 0</div>
            </div>

            <div class="connector-line"></div>

            <div class="sub-grid">
                <div class="sub-card">
                    <div class="sub-header">Kelompok Jabatan Fungsional</div>
                    <div class="sub-body">
                        <div class="position-item"><span>Analis Kebijakan Ahli Madya</span><span class="counter-badge">12 - 2 - 6</span></div>
                        <div class="position-item"><span>Analis Kebijakan Ahli Muda</span><span class="counter-badge">10 - 1 - 20</span></div>
                        <div class="position-item"><span>Analis Kebijakan Ahli Pertama</span><span class="counter-badge">8 - 0 - 15</span></div>
                        <div class="position-item"><span>Perencana Ahli Madya</span><span class="counter-badge">12 - 0 - 2</span></div>
                        <div class="position-item"><span>Perencana Ahli Muda</span><span class="counter-badge">10 - 0 - 3</span></div>
                        <div class="position-item"><span>Inspektur Migas Ahli Madya</span><span class="counter-badge">11 - 0 - 3</span></div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="sub-card">
                        <div class="sub-header">Kepala Subbagian Tata Usaha</div>
                        <div class="sub-body">
                            <div class="position-item" style="justify-content: center; background: transparent; border: none;">
                                <span class="counter-badge">9 - 0 - 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="sub-card">
                        <div class="sub-header">Jabatan Pelaksana & Arsiparis</div>
                        <div class="sub-body">
                            <div class="position-item"><span>Arsiparis Penyelia</span><span class="counter-badge">8 - 0 - 1</span></div>
                            <div class="position-item"><span>Arsiparis Mahir</span><span class="counter-badge">7 - 0 - 1</span></div>
                            <div class="position-item"><span>Penelaah Teknis Kebijakan</span><span class="counter-badge">7 - 0 - 1</span></div>
                            <div class="position-item"><span>Penata Kelola Kegiatan Usaha Hulu Migas</span><span class="counter-badge">7 - 0 - 4</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
