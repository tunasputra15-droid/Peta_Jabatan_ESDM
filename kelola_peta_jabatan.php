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
    <title>Peta Jabatan & Statistik Ringkasan - Kementerian ESDM</title>
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

        /* Top Banner Peta Jabatan */
        .peta-banner {
            background: linear-gradient(135deg, var(--primary-esdm) 0%, var(--secondary-esdm) 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(10, 25, 47, 0.1);
            border-left: 6px solid var(--accent-gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .peta-banner h1 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .btn-export-group {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            background: #1E293B;
            color: white;
            border: 1px solid #475569;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action:hover {
            background: #334155;
        }

        /* Box Struktur Direktur */
        .direktur-card-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-align: center;
            margin-bottom: 30px;
        }

        .direktur-box {
            display: inline-block;
            background: white;
            border: 2px solid var(--accent-gold);
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(197, 160, 89, 0.15);
        }

        .direktur-box .title-jabatan {
            font-size: 11px;
            font-weight: 700;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .direktur-box .nama-unit {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary-esdm);
            margin-bottom: 8px;
        }

        .badge-count {
            background: #E0F2FE;
            color: #0369A1;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Bagian Statistik Ringkasan di Bawah */
        .stat-section-wrapper {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-top: 30px;
        }

        .stat-header-banner {
            background: linear-gradient(135deg, var(--primary-esdm) 0%, var(--secondary-esdm) 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 5px solid var(--accent-gold);
        }

        .stat-header-banner h2 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-header-banner p {
            font-size: 12px;
            color: #94A3B8;
        }

        .stats-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        @media (max-width: 1024px) {
            .stats-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .stat-card-item .number-val {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-esdm);
            margin-bottom: 5px;
        }

        .stat-card-item .label-val {
            font-size: 11px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tabel Statistik Lengkap */
        .table-statistik {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-statistik th, .table-statistik td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
        }

        .table-statistik th {
            background: #F1F5F9;
            color: #475569;
            font-weight: 600;
        }

        .table-statistik td {
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
        
        <!-- Menu Kelola Peta Jabatan dengan Dropdown Interaktif -->
        <li class="has-submenu">
            <a href="#" class="dropdown-toggle active" style="background: #FACC15; color: #0A192F; border-radius: 8px; font-weight: 700;">
                <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan 
                <i class="fa-solid fa-chevron-up arrow-icon" style="margin-left: auto; font-size: 11px;"></i>
            </a>
            <!-- Submenu yang bisa buka-tutup -->
            <ul class="submenu" style="list-style: none; padding-left: 15px; margin-top: 8px; display: block;">
                <li style="margin-bottom: 6px;">
                    <a href="sekretariat_jenderal.php" style="font-size: 13px; padding: 8px 12px; color: #FACC15; text-decoration: none; display: block; font-weight: 600;">
                        1. Sekretariat Jenderal
                    </a>
                </li>
        <li style="margin-top: 10px;"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
    </ul>
</div>

    <!-- Content Utama -->
    <div class="content">
        
        <!-- BAGIAN ATAS: PETA JABATAN -->
        <div class="peta-banner">
            <h1>PETA JABATAN DIREKTORAT PEMBINAAN USAHA HULU MINYAK DAN GAS BUMI</h1>
            <div class="btn-export-group">
                <a href="#" class="btn-action"><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
                <a href="#" class="btn-action"><i class="fa-solid fa-file-code"></i> Export JSON</a>
            </div>
        </div>

        <div class="direktur-card-container">
            <div class="direktur-box">
                <div class="title-jabatan">Direktur</div>
                <div class="nama-unit">Pembinaan Usaha Hulu Minyak dan Gas Bumi</div>
                <div class="badge-count">15 - 0 - 0</div>
            </div>
        </div>

        <!-- BAGIAN BAWAH: STATISTIK RINGKASAN LENGKAP -->
        <div class="stat-section-wrapper">
            <div class="stat-header-banner">
                <h2>STATISTIK RINGKASAN PETA JABATAN</h2>
                <p>Rekapitulasi dan Analisis Formasi Jabatan — Kementerian Energi dan Sumber Daya Mineral</p>
            </div>

            <!-- Kotak Angka Ringkasan -->
            <div class="stats-cards-grid">
                <div class="stat-card-item">
                    <div class="number-val">21</div>
                    <div class="label-val">Total Jabatan</div>
                </div>
                <div class="stat-card-item">
                    <div class="number-val">56</div>
                    <div class="label-val">Total Kebutuhan</div>
                </div>
                <div class="stat-card-item">
                    <div class="number-val">88</div>
                    <div class="label-val">Total Formasi</div>
                </div>
                <div class="stat-card-item">
                    <div class="number-val" style="font-size: 15px; margin-top: 5px;">23/9/2026, 15.11</div>
                    <div class="label-val">Update Terakhir</div>
                </div>
            </div>

            <!-- Tabel Data Ringkasan Formasi Lengkap Sesuai Gambar -->
            <div style="overflow-x: auto;">
                <table class="table-statistik">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th>Formasi</th>
                            <th>Tervalidasi</th>
                            <th>Sisa Formasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Analis Kebijakan Ahli Madya</td>
                            <td>6</td>
                            <td>2</td>
                            <td><strong style="color: #EA580C;">4</strong></td>
                        </tr>
                        <tr>
                            <td>Analis Kebijakan Ahli Muda</td>
                            <td>20</td>
                            <td>1</td>
                            <td><strong style="color: #EA580C;">19</strong></td>
                        </tr>
                        <tr>
                            <td>Analis Kebijakan Ahli Pertama</td>
                            <td>15</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">15</strong></td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Madya</td>
                            <td>2</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">2</strong></td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Muda</td>
                            <td>3</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">3</strong></td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Pertama</td>
                            <td>4</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">4</strong></td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Madya</td>
                            <td>3</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">3</strong></td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Muda</td>
                            <td>5</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">5</strong></td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Pertama</td>
                            <td>5</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">5</strong></td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Madya</td>
                            <td>3</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">3</strong></td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Muda</td>
                            <td>5</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">5</strong></td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Pertama</td>
                            <td>6</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">6</strong></td>
                        </tr>
                        <tr>
                            <td>Penata Kelola Kegiatan Usaha Hulu Migas</td>
                            <td>4</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">4</strong></td>
                        </tr>
                        <tr>
                            <td>Kepala Subbagian Tata Usaha</td>
                            <td>0</td>
                            <td>0</td>
                            <td><strong style="color: #10B981;">0</strong></td>
                        </tr>
                        <tr>
                            <td>Arsiparis Penyelia</td>
                            <td>1</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">1</strong></td>
                        </tr>
                        <tr>
                            <td>Arsiparis Mahir</td>
                            <td>1</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">1</strong></td>
                        </tr>
                        <tr>
                            <td>Arsiparis Terampil</td>
                            <td>1</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">1</strong></td>
                        </tr>
                        <tr>
                            <td>Penelaah Teknis Kebijakan</td>
                            <td>1</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">1</strong></td>
                        </tr>
                        <tr>
                            <td>Pengelola Layanan Operasional</td>
                            <td>1</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">1</strong></td>
                        </tr>
                        <tr>
                            <td>Pengadministrasi Perkantoran</td>
                            <td>2</td>
                            <td>0</td>
                            <td><strong style="color: #EA580C;">2</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<script>
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const submenu = document.querySelector('.submenu');
    const arrowIcon = document.querySelector('.arrow-icon');

    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah link pindah halaman langsung jika hanya ingin toggle
            
            // Cek apakah submenu sedang terbuka atau tertutup
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
                arrowIcon.classList.remove('fa-chevron-up');
                arrowIcon.classList.add('fa-chevron-down');
            } else {
                submenu.style.display = 'block';
                arrowIcon.classList.remove('fa-chevron-down');
                arrowIcon.classList.add('fa-chevron-up');
            }
        });
    }
</script> 
    
</body>
</html>
