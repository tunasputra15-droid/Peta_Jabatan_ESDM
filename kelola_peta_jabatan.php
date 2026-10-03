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
    <title>Peta Jabatan - Sekretariat Jenderal</title>
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
        .top-banner {background-color: var(--accent-gold);padding: 16px 30px;font-weight: 800;font-size: 14px;color: var(--primary-esdm);box-shadow: 0 2px 5px rgba(0,0,0,0.05);}
        .user-badge { width: 32px; height: 32px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }

        /* Content Container */
        .container { padding: 30px 40px; max-width: 1600px; margin: 0 auto; width: 100%; }

        /* Breadcrumb & Title */
        .back-link { font-size: 12px; color: #D97706; text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 8px; }
        .back-link:hover { text-decoration: underline; }
        .page-title { font-size: 22px; font-weight: 800; color: #000; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px; text-align: center; }

        /* Stat Card Baris (Lebar Penuh) */
        .stat-card-row { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 15px 25px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-left { display: flex; align-items: center; gap: 15px; }
        .stat-icon-box { width: 36px; height: 36px; background: #FEF9C3; border: 1px solid #FDE047; color: #854D0E; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .stat-label-text { font-size: 13px; font-weight: 800; color: #1E293B; letter-spacing: 0.3px; }
        
        .stat-right-values { display: flex; gap: 80px; align-items: center; }
        .stat-val-item { text-align: right; }
        .stat-val-item .title { font-size: 9px; font-weight: 700; color: #64748B; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .stat-val-item .number-blue { font-size: 15px; font-weight: 800; color: #2563EB; }
        .stat-val-item .number-green { font-size: 15px; font-weight: 800; color: #059669; }
        .stat-val-item .number-red { font-size: 15px; font-weight: 800; color: #DC2626; }

        /* Struktur Organisasi Section dengan Garis Penghubung Emas */
        .org-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 25px; margin-top: 30px; margin-bottom: 30px; overflow-x: auto; }
        .org-section h4 { font-size: 13px; font-weight: 700; color: #64748B; margin-bottom: 25px; }
        
        .org-tree { display: flex; flex-direction: column; align-items: center; min-width: 1250px; padding-bottom: 15px; }
        
        /* Kotak Induk (Sekjen) */
        .parent-node { 
            background: var(--accent-gold); 
            color: #000; 
            font-weight: 800; 
            font-size: 13px; 
            padding: 10px 30px; 
            border-radius: 6px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
            border: 1px solid #EAB308; 
            position: relative;
            margin-bottom: 25px;
            z-index: 2; 
        }
        
        /* Garis Vertikal dari Induk ke Batang Horizontal */
        .parent-node::after {
            content: '';
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 25px;
            background-color: #D97706;
        }

        /* Container Batang Horizontal Penghubung 10 Anak */
        .tree-horizontal-line {
            position: relative;
            width: 92%;
            height: 3px;
            background-color: #D97706;
            margin-bottom: 20px;
        }

        /* Grid 10 Anak Eselon II */
        .children-nodes { display: grid; grid-template-columns: repeat(10, 1fr); gap: 10px; width: 100%; z-index: 2; }
        
        .child-card { 
            background: #FFFFFF; 
            border: 1px solid #CBD5E1; 
            border-radius: 6px; 
            padding: 8px 6px; 
            text-align: center; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.04); 
            position: relative;
        }
        
        /* Garis Vertikal Kecil dari Batang Horizontal ke Masing-masing Kartu Anak */
        .child-card::before {
            content: '';
            position: absolute;
            top: -23px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 23px;
            background-color: #D97706;
        }

        .child-card .unit-name { font-size: 9.5px; font-weight: 700; color: #1E293B; height: 35px; display: flex; align-items: center; justify-content: center; margin-bottom: 6px; line-height: 1.15; }
        .child-stats { display: flex; justify-content: space-around; border-top: 1px solid #F1F5F9; padding-top: 4px; font-size: 8.5px; font-weight: 700; }
        .child-stats span { display: block; }
        .child-stats .c-label { font-size: 7.5px; color: #64748B; }
        .child-stats .c-keb { color: #2563EB; font-size: 9px; }
        .child-stats .c-eks { color: #059669; font-size: 9px; }
        .child-stats .c-sel { color: #DC2626; font-size: 9px; }

        /* Tabel Rangkuman Unit Eselon II */
        .table-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; overflow: hidden; margin-top: 20px; }
        .table-header-bar { background: var(--accent-gold); padding: 12px 20px; font-size: 13px; font-weight: 800; color: #000; }
        
        .eselon2-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .eselon2-table th, .eselon2-table td { padding: 12px 20px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .eselon2-table th { background: #F8FAFC; color: #475569; font-weight: 700; font-size: 11px; text-transform: uppercase; }
        .eselon2-table td { font-weight: 600; color: #334155; }
        .eselon2-table td.num-b { color: #2563EB; text-align: right; }
        .eselon2-table td.num-e { color: #059669; text-align: right; }
        .eselon2-table td.num-s { color: #DC2626; text-align: right; font-weight: 800; }
        .eselon2-table th:nth-child(2), .eselon2-table th:nth-child(3), .eselon2-table th:nth-child(4) { text-align: right; }
        .eselon2-table tr:hover { background-color: #F8FAFC; }
    </style>
</head>
<body>

    <!-- Header Kuning Atas -->
    <div class="top-banner"><span>Sekretariat Jenderal</span>
    </div>

    <!-- Main Container -->
    <div class="container">
        
        <!-- Breadcrumb & Judul -->
            <div style="text-align: center; margin-bottom: 5px;">
            <div style="font-size: 22px; font-weight: 800; color: #000; text-transform: uppercase; letter-spacing: 0.5px;">Peta Jabatan</div>
      </div>
            <div style="font-size: 16px; font-weight: 600; color: #475569; margin-bottom: 20px;">Sekretariat Jenderal
      </div>
      
        <!-- Kartu Statistik Utama -->
        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-users"></i></div><div class="stat-label-text">TOTAL PEGAWAI</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">820</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">765</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-55</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-tie"></i></div><div class="stat-label-text">JABATAN STRUKTURAL</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">41</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">40</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-1</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-gear"></i></div><div class="stat-label-text">JAB. ADMIN / PENGAWAS</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">82</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">73</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-8</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-graduation-cap"></i></div><div class="stat-label-text">JABATAN FUNGSIONAL</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">492</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">451</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-41</span></div>
            </div>
        </div>

        <div class="stat-card-row">
            <div class="stat-left"><div class="stat-icon-box"><i class="fa-solid fa-user-shield"></i></div><div class="stat-label-text">JABATAN PELAKSANA</div></div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">205</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">188</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-16</span></div>
            </div>
        </div>

        <!-- Struktur Organisasi Eselon II Lengkap dengan 10 Bagan & Garis Penghubung -->
        <div class="org-section">
            <h4>Struktur Organisasi 1. Sekretariat Jenderal</h4>
            <div class="org-tree">
                <!-- Kotak Induk -->
                <div class="parent-node">1. Sekretariat Jenderal</div>
                
                <!-- Batang Garis Horizontal Penghubung -->
                <div class="tree-horizontal-line"></div>

                <!-- 10 Kartu Eselon II di Bawah -->
                <div class="children-nodes">
                    <!-- 1 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Perencanaan</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">142</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">120</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-22</span></div>
                        </div>
                    </div>
                    <!-- 2 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Organisasi & SDM</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">95</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">88</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-7</span></div>
                        </div>
                    </div>
                    <!-- 3 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Keuangan</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">80</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">75</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                    <!-- 4 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Hukum</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">65</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">60</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                    <!-- 5 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Adm. Pimpinan</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">85</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">80</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                    <!-- 6 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Komunikasi</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">75</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">70</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                    <!-- 7 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Umum</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">150</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">145</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                    <!-- 8 -->
                    <div class="child-card">
                        <div class="unit-name">PPSDM</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">50</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">48</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-2</span></div>
                        </div>
                    </div>
                    <!-- 9 -->
                    <div class="child-card">
                        <div class="unit-name">PUSDATIN</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">85</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">75</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-10</span></div>
                        </div>
                    </div>
                    <!-- 10 -->
                    <div class="child-card">
                        <div class="unit-name">PUSAKA</div>
                        <div class="child-stats">
                            <div><span class="c-label">KEB</span><span class="c-keb">45</span></div>
                            <div><span class="c-label">EKS</span><span class="c-eks">40</span></div>
                            <div><span class="c-label">SEL</span><span class="c-sel">-5</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rangkuman Unit Eselon II -->
        <div class="table-section">
            <div class="table-header-bar">Rangkuman Unit Eselon II</div>
            <table class="eselon2-table">
                <thead>
                    <tr>
                        <th>Nama Unit Kerja Eselon II</th>
                        <th>Kebutuhan</th>
                        <th>Eksisting</th>
                        <th>Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1. Biro Perencanaan</td><td class="num-b">142</td><td class="num-e">120</td><td class="num-s">-22</td></tr>
                    <tr><td>2. Biro Organisasi dan Sumber Daya Manusia</td><td class="num-b">95</td><td class="num-e">88</td><td class="num-s">-7</td></tr>
                    <tr><td>3. Biro Keuangan</td><td class="num-b">80</td><td class="num-e">75</td><td class="num-s">-5</td></tr>
                    <tr><td>4. Biro Hukum</td><td class="num-b">65</td><td class="num-e">60</td><td class="num-s">-5</td></tr>
                    <tr><td>5. Biro Administrasi Pimpinan Dan Protokol</td><td class="num-b">85</td><td class="num-e">80</td><td class="num-s">-5</td></tr>
                    <tr><td>6. Biro Komunikasi</td><td class="num-b">75</td><td class="num-e">70</td><td class="num-s">-5</td></tr>
                    <tr><td>7. Biro Umum</td><td class="num-b">150</td><td class="num-e">145</td><td class="num-s">-5</td></tr>
                    <tr><td>8. PPSMN</td><td class="num-b">50</td><td class="num-e">48</td><td class="num-s">-2</td></tr>
                    <tr><td>9. PUSDATIN</td><td class="num-b">85</td><td class="num-e">75</td><td class="num-s">-10</td></tr>
                    <tr><td>10. PUSAKA</td><td class="num-b">45</td><td class="num-e">40</td><td class="num-s">-5</td></tr>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
