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
    <title>Kelola Peta Jabatan & Statistik - Kementerian ESDM</title>
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

        /* Sidebar Putih Bersih */
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

        .sidebar ul li a i {
            color: #94A3B8;
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

        .sidebar ul li a.active i {
            color: var(--accent-gold);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

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
            margin-bottom: 6px;
        }

        .top-banner p {
            font-size: 13px;
            color: #94A3B8;
        }

        /* Bagian Statistik Ringkasan (Isi Asli) */
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary-esdm);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-header span {
            font-size: 12px;
            color: #64748B;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-header i {
            color: var(--accent-gold);
            font-size: 18px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-esdm);
        }

        /* Card Utama Peta Jabatan */
        .main-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #E2E8F0;
        }

        .btn-tambah {
            background: #059669;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-custom th, .table-custom td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }

        .table-custom th {
            background: #F8FAFC;
            color: #475569;
            font-weight: 600;
        }

        .table-custom td {
            color: var(--text-main);
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
            <li><a href="profil.php"><i class="fa-solid fa-user-shield"></i> Profil Pegawai</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="top-banner">
            <h1>KELOLA PETA JABATAN & STATISTIK</h1>
            <p>Monitoring formasi jabatan serta rekapitulasi data kepegawaian unit kerja</p>
        </div>

        <!-- 1. BAGIAN STATISTIK RINGKASAN (Isi asli dipindah ke sini) -->
        <div class="section-title">
            <i class="fa-solid fa-chart-column"></i> Statistik Ringkasan Unit Kerja
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span>Total Formasi</span>
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div class="stat-value">42</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span>Pegawai Terisi</span>
                    <i class="fa-solid fa-user-check" style="color: #059669;"></i>
                </div>
                <div class="stat-value">38</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span>Formasi Kosong</span>
                    <i class="fa-solid fa-user-slash" style="color: #DC2626;"></i>
                </div>
                <div class="stat-value">4</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span>Tingkat Pengisian</span>
                    <i class="fa-solid fa-percent" style="color: #2563EB;"></i>
                </div>
                <div class="stat-value">90.4%</div>
            </div>
        </div>

        <!-- 2. BAGIAN KELOLA PETA JABATAN -->
        <div class="main-card">
            <div class="card-header-flex">
                <div class="section-title" style="margin-bottom: 0;">
                    <i class="fa-solid fa-list-check"></i> Daftar Peta Jabatan
                </div>
                <a href="#" class="btn-tambah"><i class="fa-solid fa-plus"></i> Tambah Jabatan</a>
            </div>

            <div class="table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Jabatan</th>
                            <th>Kelas Jabatan</th>
                            <th>Eselon / Jenjang</th>
                            <th>Pemangku Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Analis Kebijakan Ahli Madya</td>
                            <td>11</td>
                            <td>Ahli Madya</td>
                            <td>Wahyu Setyoaji, S.T.</td>
                            <td>
                                <a href="#" style="color: #2563EB; margin-right: 10px;"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#" style="color: #DC2626;"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Pengadministrasi Umum</td>
                            <td>6</td>
                            <td>Pelaksana</td>
                            <td>- (Kosong)</td>
                            <td>
                                <a href="#" style="color: #2563EB; margin-right: 10px;"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#" style="color: #DC2626;"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
