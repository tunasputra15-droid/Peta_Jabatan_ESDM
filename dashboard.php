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

        /* Header / Banner */
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
            font-size: 20px;
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

        /* Statistik Ringkasan & Tabel Section */
        .stats-section {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #E2E8F0;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .stats-header {
            padding: 20px 25px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #FAFCFF;
        }

        .stats-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-esdm);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stats-header h3 i {
            color: var(--accent-gold);
        }

        /* Summary Cards Grid */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 25px;
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
        }

        .summary-card-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            text-align: center;
        }

        .summary-card-item .num {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-esdm);
            margin-bottom: 5px;
        }

        .summary-card-item .label {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Data Table Styling */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        .data-table th {
            background-color: #F1F5F9;
            color: var(--primary-esdm);
            font-weight: 600;
            padding: 14px 20px;
            border-bottom: 2px solid #E2E8F0;
        }

        .data-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #F1F5F9;
            color: var(--text-main);
        }

        .data-table tr:hover {
            background-color: #F8FAFC;
        }

        .text-orange {
            color: #D97706;
            font-weight: 700;
        }

        .text-green {
            color: #059669;
            font-weight: 700;
        }

        /* Info Panel */
        .info-panel {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            border-left: 5px solid var(--primary-esdm);
        }

        .info-panel h3 {
            font-size: 16px;
            color: var(--primary-esdm);
            margin-bottom: 10px;
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
            font-size: 13px;
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
            <li><a href="kelola_peta_jabatan.php"><i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan</a></li>
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

        <!-- Bagian Statistik Ringkasan & Tabel Rincian Data -->
        <div class="stats-section">
            <div class="stats-header">
                <h3><i class="fa-solid fa-chart-column"></i> Statistik Ringkasan</h3>
            </div>

            <!-- Kartu Angka Ringkasan -->
            <div class="summary-cards">
                <div class="summary-card-item">
                    <div class="num">21</div>
                    <div class="label">Total Jabatan</div>
                </div>
                <div class="summary-card-item">
                    <div class="num">56</div>
                    <div class="label">Total Kebutuhan</div>
                </div>
                <div class="summary-card-item">
                    <div class="num">88</div>
                    <div class="label">Total Formasi</div>
                </div>
                <div class="summary-card-item">
                    <div class="num" style="font-size: 15px; margin-top: 6px; color: #64748B;">23/9/2026, 15.11.25</div>
                    <div class="label">Update Terakhir</div>
                </div>
            </div>

            <!-- Tabel Rincian Data Formasi -->
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th style="text-align: center;">Formasi</th>
                            <th style="text-align: center;">Tervalidasi</th>
                            <th style="text-align: center;">Sisa Formasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Analis Kebijakan Ahli Madya</td>
                            <td style="text-align: center;">6</td>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;" class="text-orange">4</td>
                        </tr>
                        <tr>
                            <td>Analis Kebijakan Ahli Muda</td>
                            <td style="text-align: center;">20</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;" class="text-orange">19</td>
                        </tr>
                        <tr>
                            <td>Analis Kebijakan Ahli Pertama</td>
                            <td style="text-align: center;">15</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">15</td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Madya</td>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">2</td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Muda</td>
                            <td style="text-align: center;">3</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">3</td>
                        </tr>
                        <tr>
                            <td>Perencana Ahli Pertama</td>
                            <td style="text-align: center;">4</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">4</td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Madya</td>
                            <td style="text-align: center;">3</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">3</td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Muda</td>
                            <td style="text-align: center;">5</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">5</td>
                        </tr>
                        <tr>
                            <td>Inspektur Migas Ahli Pertama</td>
                            <td style="text-align: center;">5</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">5</td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Madya</td>
                            <td style="text-align: center;">3</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">3</td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Muda</td>
                            <td style="text-align: center;">5</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">5</td>
                        </tr>
                        <tr>
                            <td>Penata Perizinan Ahli Pertama</td>
                            <td style="text-align: center;">6</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">6</td>
                        </tr>
                        <tr>
                            <td>Penata Kelola Kegiatan Usaha Hulu Migas</td>
                            <td style="text-align: center;">4</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">4</td>
                        </tr>
                        <tr>
                            <td>Kepala Subbagian Tata Usaha</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-green">0</td>
                        </tr>
                        <tr>
                            <td>Arsiparis Penyelia</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">1</td>
                        </tr>
                        <tr>
                            <td>Arsiparis Mahir</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">1</td>
                        </tr>
                        <tr>
                            <td>Arsiparis Terampil</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">1</td>
                        </tr>
                        <tr>
                            <td>Penelaah Teknis Kebijakan</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">1</td>
                        </tr>
                        <tr>
                            <td>Pengelola Layanan Operasional</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">1</td>
                        </tr>
                        <tr>
                            <td>Pengadministrasi Perkantoran</td>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;">0</td>
                            <td style="text-align: center;" class="text-orange">2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Informasi / Operasional -->
        <div class="info-panel">
            <h3><i class="fa-solid fa-circle-info"></i> Panel Operasional Admin Unit</h3>
            <p>
                Halaman utama ini menyajikan rekapitulasi data dan statistik ringkasan peta jabatan di lingkungan unit kerja Kementerian ESDM. Gunakan menu di sebelah kiri untuk mengelola rincian peta jabatan secara menyeluruh.
            </p>
        </div>
    </div>

</body>
</html>
