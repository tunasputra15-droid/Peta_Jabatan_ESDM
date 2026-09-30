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
    <title>Statistik Ringkasan - Kementerian ESDM</title>
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

        /* Statistik Section */
        .stats-section {
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }

        .stats-header {
            padding: 20px 25px;
            border-bottom: 1px solid #E2E8F0;
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
            <li><a href="kelola_peta_jabatan.php"><i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan</a></li>
            <li><a href="statistik.php" class="active"><i class="fa-solid fa-chart-column"></i> Statistik Ringkasan</a></li>
            <li><a href="#"><i class="fa-solid fa-pen-to-square"></i> Usulan Tambah/Edit</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a></li>
        </ul>
    </div>

    <!-- Content Area -->
    <div class="content">
        <div class="top-banner">
            <h1>STATISTIK RINGKASAN PETA JABATAN</h1>
            <p>Rekapitulasi dan Analisis Formasi Jabatan — Kementerian Energi dan Sumber Daya Mineral</p>
        </div>

        <div class="stats-section">
            <div class="stats-header">
                <h3><i class="fa-solid fa-chart-column"></i> Data Ringkasan Formasi</h3>
            </div>

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
                    <div class="num" id="realtime-clock" style="font-size: 15px; margin-top: 6px; color: #64748B;">Memuat waktu...</div>
                    <div class="label">Update Terakhir</div>
                    </div>
            </div>

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
    </div>
<script>
    function updateClock() {
        const now = new Date();
        
        // Format Tanggal: DD/MM/YYYY
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        
        // Format Waktu: HH:MM:SS
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        // Gabungkan tampilan
        const formattedDateTime = `${day}/${month}/${year}, ${hours}:${minutes}:${seconds}`;
        
        // Masukkan ke elemen HTML
        document.getElementById('realtime-clock').innerText = formattedDateTime;
    }
    
    // Jalankan fungsi langsung saat halaman dibuka, lalu update setiap 1 detik
    updateClock();
    setInterval(updateClock, 1000);
</script>
</body>
</html>
