<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jabatan Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --accent-gold: #FFC107;
            --bg-body: #FDFBF7;
            --text-main: #334155;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); display: flex; flex-direction: column; min-height: 100vh; }

        /* Header Kuning Atas */
        .top-banner { background-color: var(--accent-gold); padding: 16px 30px; font-weight: 800; font-size: 14px; color: var(--primary-esdm); box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .user-badge { width: 32px; height: 32px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; }

        /* Container */
        .container { padding: 30px 40px; max-width: 1600px; margin: 0 auto; width: 100%; }

        /* Judul Halaman dengan Garis Pembatas Abu-Abu Lembut */
        .page-header-box { 
            margin-bottom: 25px; 
            border-bottom: 1.5px solid #CBD5E1; 
            padding-bottom: 15px; 
        }
        .page-title { font-size: 22px; font-weight: 800; color: #000; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .page-subtitle { font-size: 11px; font-weight: 600; color: #64748B; }

        /* Stat Card Baris (Lebar Penuh) */
        .stat-card-row { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px 25px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-left { display: flex; align-items: center; gap: 15px; }
        .stat-icon-box { width: 34px; height: 34px; background: #FEF9C3; border: 1px solid #FDE047; color: #854D0E; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .stat-label-text { font-size: 12px; font-weight: 800; color: #1E293B; letter-spacing: 0.3px; }
        
        .stat-right-values { display: flex; gap: 80px; align-items: center; }
        .stat-val-item { text-align: right; }
        .stat-val-item .title { font-size: 8.5px; font-weight: 700; color: #64748B; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .stat-val-item .number-blue { font-size: 14px; font-weight: 800; color: #2563EB; }
        .stat-val-item .number-green { font-size: 14px; font-weight: 800; color: #059669; }
        .stat-val-item .number-red { font-size: 14px; font-weight: 800; color: #DC2626; }

        /* Tabel Rangkuman Unit Eselon I */
        .table-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; margin-top: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .table-header-bar { background: var(--accent-gold); padding: 12px 20px; font-size: 12px; font-weight: 800; color: #000; }
        
        .eselon1-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .eselon1-table th, .eselon1-table td { padding: 12px 20px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .eselon1-table th { background: #F8FAFC; color: #475569; font-weight: 700; font-size: 10.5px; text-transform: uppercase; }
        .eselon1-table td { font-weight: 600; color: #334155; }
        .eselon1-table td.num-b { color: #2563EB; text-align: right; }
        .eselon1-table td.num-e { color: #059669; text-align: right; }
        .eselon1-table td.num-s { color: #DC2626; text-align: right; font-weight: 800; }
        .eselon1-table th:nth-child(2), .eselon1-table th:nth-child(3), .eselon1-table th:nth-child(4) { text-align: right; }
        .eselon1-table tr:hover { background-color: #F8FAFC; }
        
        .unit-link { color: #1E293B; text-decoration: none; font-weight: 700; }
        .unit-link:hover { color: #D97706; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Header Kuning Atas -->
    <div class="top-banner">
        <span>Kementerian ESDM</span>
    </div>

    <!-- Main Container -->
    <div class="container">
        
        <!-- Judul Halaman dengan Garis Pembatas -->
        <div class="page-header-box">
            <div class="page-title">Peta Jabatan Kementerian ESDM</div>
            <div class="page-subtitle">Rekapitulasi Kebutuhan dan Eksisting Pegawai Tingkat Kementerian</div>
        </div>

        <!-- Kartu Statistik Utama -->
        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-users"></i></div><div class="stat-label-text">TOTAL PEGAWAI</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">6240</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">5892</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-348</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-tie"></i></div><div class="stat-label-text">JABATAN STRUKTURAL</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">125</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">118</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-7</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-gear"></i></div><div class="stat-label-text">JAB. ADMIN / PENGAWAS</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">450</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">412</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-38</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-graduation-cap"></i></div><div class="stat-label-text">JABATAN FUNGSIONAL</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">4120</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">3950</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-170</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-shield"></i></div><div class="stat-label-text">JABATAN PELAKSANA</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">1545</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">1412</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-133</span></div>
            </div>
        </div>

        <!-- Tabel Rangkuman Unit Eselon I -->
        <div class="table-section">
            <div class="table-header-bar">Rangkuman Unit Eselon I</div>
            <table class="eselon1-table">
                <thead>
                    <tr>
                        <th>NAMA UNIT KERJA ESELON I</th>
                        <th>KEBUTUHAN</th>
                        <th>EKSISTING</th>
                        <th>SELISIH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="sekretariat_jenderal.php" class="unit-link">1. Sekretariat Jenderal</a></td>
                        <td class="num-b">820</td>
                        <td class="num-e">765</td>
                        <td class="num-s">-55</td>
                    </tr>
                    <tr>
                        <td>2. Direktorat Jenderal Minyak dan Gas Bumi</td>
                        <td class="num-b">600</td>
                        <td class="num-e">550</td>
                        <td class="num-s">-50</td>
                    </tr>
                    <tr>
                        <td>3. Direktorat Jenderal Ketenagalistrikan</td>
                        <td class="num-b">450</td>
                        <td class="num-e">420</td>
                        <td class="num-s">-30</td>
                    </tr>
                    <tr>
                        <td>4. Direktorat Jenderal Mineral Dan Batubara</td>
                        <td class="num-b">700</td>
                        <td class="num-e">680</td>
                        <td class="num-s">-20</td>
                    </tr>
                    <tr>
                        <td>5. Ditjen Energi Baru, Terbarukan & Konservasi Energi</td>
                        <td class="num-b">500</td>
                        <td class="num-e">470</td>
                        <td class="num-s">-30</td>
                    </tr>
                    <tr>
                        <td>6. Direktorat Jenderal Penegakan Hukum ESDM</td>
                        <td class="num-b">400</td>
                        <td class="num-e">350</td>
                        <td class="num-s">-50</td>
                    </tr>
                    <tr>
                        <td>7. Inspektorat Jenderal</td>
                        <td class="num-b">350</td>
                        <td class="num-e">340</td>
                        <td class="num-s">-10</td>
                    </tr>
                    <tr>
                        <td>8. Badan Geologi</td>
                        <td class="num-b">850</td>
                        <td class="num-e">800</td>
                        <td class="num-s">-50</td>
                    </tr>
                    <tr>
                        <td>9. Badan Pengembangan SDM ESDM</td>
                        <td class="num-b">650</td>
                        <td class="num-e">620</td>
                        <td class="num-s">-30</td>
                    </tr>
                    <tr>
                        <td>10. Sekretariat Jenderal Dewan Energi Nasional</td>
                        <td class="num-b">220</td>
                        <td class="num-e">200</td>
                        <td class="num-s">-20</td>
                    </tr>
                    <tr>
                        <td>11. Badan Pengatur Hilir Minyak Dan Gas Bumi</td>
                        <td class="num-b">700</td>
                        <td class="num-e">697</td>
                        <td class="num-s">-3</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
