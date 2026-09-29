<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
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
            --accent-gold-light: rgba(197, 160, 89, 0.1);
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

        /* Sidebar Styling */
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
            background-color: var(--accent-gold-light);
            color: #ffffff;
            border-left: 4px solid var(--accent-gold);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Modern Professional Banner */
        .top-banner {
            background: linear-gradient(135deg, var(--primary-esdm) 0%, var(--secondary-esdm) 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(10, 25, 47, 0.1);
            border-left: 6px solid var(--accent-gold);
        }

        .top-banner h1 {
            font-size: 19px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .top-banner p {
            font-size: 13px;
            color: #94A3B8;
            font-weight: 400;
        }

        /* Toolbar & Controls */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
            flex-wrap: wrap;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 350px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 42px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 14px;
            background: #F8FAFC;
            outline: none;
            transition: all 0.2s;
        }

        .search-box input:focus {
            border-color: var(--accent-gold);
            background: white;
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.15);
        }

        .search-box i {
            position: absolute;
            left: 15px;
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
            transition: all 0.2s;
        }

        .btn-action:hover {
            background-color: var(--secondary-esdm);
            transform: translateY(-1px);
        }

        /* Org Chart Hierarchy Styling */
        .org-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* Top Director Card */
        .box-direktur {
            background: white;
            border: 2px solid var(--accent-gold);
            border-radius: 14px;
            padding: 22px 35px;
            text-align: center;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.12);
            position: relative;
        }

        .box-direktur .role-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--accent-gold);
            letter-spacing: 1.5px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .box-direktur .role-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-esdm);
            margin-bottom: 14px;
        }

        .counter-badge {
            display: inline-block;
            background: #F1F5F9;
            color: #475569;
            padding: 5px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            border: 1px solid #E2E8F0;
        }

        .connector-vertical {
            width: 3px;
            height: 30px;
            background: linear-gradient(to bottom, var(--accent-gold), #94A3B8);
        }

        /* Subordinate Grid Layout */
        .sub-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .sub-header {
            background: var(--primary-esdm);
            color: white;
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sub-header i {
            color: var(--accent-gold);
        }

        .sub-body {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .position-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: #F8FAFC;
            border: 1px solid #F1F5F9;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .position-item:hover {
            background: #EDF2F7;
            border-color: #CBD5E1;
            transform: translateX(2px);
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
        <!-- Professional Top Banner (Tanpa Tulisan Admin Panel) -->
        <div class="top-banner">
            <h1>PETA JABATAN DIREKTORAT PEMBINAAN USAHA HULU MINYAK DAN GAS BUMI</h1>
            <p>Direktorat Jenderal Minyak dan Gas Bumi — Kementerian Energi dan Sumber Daya Mineral</p>
        </div>

        <!-- Toolbar Pencarian & Aksi Data -->
        <div class="toolbar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari nama jabatan atau fungsional...">
            </div>
            <div class="action-buttons">
                <button class="btn-action"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
                <button class="btn-action"><i class="fa-solid fa-code"></i> Export JSON</button>
                <button class="btn-action"><i class="fa-solid fa-upload"></i> Import JSON</button>
            </div>
        </div>

        <!-- Struktur Peta Organisasi -->
        <div class="org-container">
            <!-- Pimpinan Tertinggi -->
            <div class="box-direktur">
                <div class="role-title">Direktur</div>
                <div class="role-name">Pembinaan Usaha Hulu Minyak dan Gas Bumi</div>
                <div class="counter-badge">15 - 0 - 0</div>
            </div>

            <div class="connector-vertical"></div>

            <!-- Cabang Struktur Bawah -->
            <div class="sub-grid">
                <!-- Kolom Kelompok Jabatan Fungsional -->
                <div class="sub-card">
                    <div class="sub-header">
                        <i class="fa-solid fa-users-gear"></i> Kelompok Jabatan Fungsional
                    </div>
                    <div class="sub-body">
                        <div class="position-item"><span>Analis Kebijakan Ahli Madya</span><span class="counter-badge">12 - 2 - 6</span></div>
                        <div class="position-item"><span>Analis Kebijakan Ahli Muda</span><span class="counter-badge">10 - 1 - 20</span></div>
                        <div class="position-item"><span>Analis Kebijakan Ahli Pertama</span><span class="counter-badge">8 - 0 - 15</span></div>
                        <div class="position-item"><span>Perencana Ahli Madya</span><span class="counter-badge">12 - 0 - 2</span></div>
                        <div class="position-item"><span>Perencana Ahli Muda</span><span class="counter-badge">10 - 0 - 3</span></div>
                        <div class="position-item"><span>Inspektur Migas Ahli Madya</span><span class="counter-badge">11 - 0 - 3</span></div>
                    </div>
                </div>

                <!-- Kolom Subbagian TU & Pelaksana -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div class="sub-card">
                        <div class="sub-header">
                            <i class="fa-solid fa-user-tie"></i> Kepala Subbagian Tata Usaha
                        </div>
                        <div class="sub-body">
                            <div class="position-item" style="justify-content: center; background: transparent; border: none;">
                                <span class="counter-badge" style="background: white; font-weight: 700;">9 - 0 - 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="sub-card">
                        <div class="sub-header">
                            <i class="fa-solid fa-address-card"></i> Jabatan Pelaksana & Arsiparis
                        </div>
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
