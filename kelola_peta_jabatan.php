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
    <title>Peta Jabatan Kementerian ESDM - Admin Unit</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --accent-gold: #FFC107;
            --bg-body: #F4F6F9;
            --text-main: #334155;
            --sidebar-width: 280px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; }

        /* Sidebar Kiri (Background Putih sesuai referensi gambar) */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; border-right: 1px solid #E2E8F0; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; overflow-y: auto; }
        .sidebar-brand { padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; font-weight: 800; color: var(--primary-esdm); }
        .sidebar-brand i { font-size: 16px; color: #D97706; background: #FEF9C3; padding: 8px; border-radius: 6px; }

        .sidebar-menu { padding: 20px 12px; display: flex; flex-direction: column; gap: 6px; flex-grow: 1; }
        .menu-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; color: #64748B; transition: 0.2s; cursor: pointer; }
        .menu-item-left { display: flex; align-items: center; gap: 12px; }
        .menu-item i { font-size: 14px; width: 20px; text-align: center; }
        
        .menu-item:hover { background: #F8FAFC; color: var(--primary-esdm); }
        
        /* Menu Dropdown Aktif (Background Kuning Lembut sesuai referensi) */
        .menu-item.dropdown-toggle.active { background: #FEF3C7; color: #92400E; font-weight: 800; }
        .menu-item.dropdown-toggle.active i { color: #D97706; }

        /* Submenu Container */
        .submenu-container { display: none; flex-direction: column; padding-left: 15px; margin-top: 4px; gap: 4px; }
        .submenu-container.show { display: flex; }
        
        .submenu-item { padding: 10px 14px; border-radius: 6px; text-decoration: none; font-size: 11.5px; font-weight: 600; color: #64748B; transition: 0.2s; line-height: 1.3; }
        .submenu-item:hover { background: #F8FAFC; color: var(--primary-esdm); }
        .submenu-item.sub-active { color: #D97706; font-weight: 700; background: #FEF9C3; }

        /* Main Content Wrapper */
        .main-wrapper { margin-left: var(--sidebar-width); flex-grow: 1; display: flex; flex-direction: column; min-height: 100vh; }

        /* Top Header Bar di Kanan (Background Kuning sesuai permintaan) */
        .top-header { background: var(--accent-gold); color: var(--primary-esdm); padding: 16px 35px; font-size: 15px; font-weight: 800; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .top-header-right { display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 700; color: var(--primary-esdm); }
        .user-avatar { width: 30px; height: 30px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 11px; }

        /* Container Isi Halaman */
        .container { padding: 30px 35px; max-width: 1400px; width: 100%; }

        /* Judul Halaman dengan Garis Pembatas Abu-Abu Lembut */
        .page-header-box { 
            margin-bottom: 25px; 
            border-bottom: 1.5px solid #CBD5E1; 
            padding-bottom: 15px; 
        }
        .page-title { font-size: 20px; font-weight: 800; color: #000; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .page-subtitle { font-size: 11px; font-weight: 600; color: #64748B; }

        /* Stat Card Baris (Lebar Penuh) */
        .stat-card-row { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px 25px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-left { display: flex; align-items: center; gap: 15px; }
        .stat-icon-box { width: 34px; height: 34px; background: #FEF9C3; border: 1px solid #FDE047; color: #854D0E; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 13px; }
        .stat-label-text { font-size: 12px; font-weight: 800; color: #1E293B; letter-spacing: 0.3px; }
        
        .stat-right-values { display: flex; gap: 60px; align-items: center; }
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

    <!-- Sidebar Kiri (Background Putih) -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-building-shield"></i>
            <span>ADMIN UNIT ESDM</span>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item">
                <div class="menu-item-left">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </div>
            </a>
            
            <!-- Menu Kelola Peta Jabatan dengan Dropdown Interaktif -->
            <div>
                <div class="menu-item dropdown-toggle active" id="dropdownBtn">
                    <div class="menu-item-left">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>Kelola Peta Jabatan</span>
                    </div>
                    <i class="fa-solid fa-chevron-up" id="arrowIcon" style="font-size: 11px;"></i>
                </div>
                <!-- Daftar Submenu Eselon I -->
                <div class="submenu-container show" id="submenuList">
                    <a href="sekretariat_jenderal.php" class="submenu-item sub-active"> Sekretariat Jenderal</a>
                    <a href="#" class="submenu-item"> Direktorat Jenderal Minyak dan Gas Bumi</a>
                    <a href="#" class="submenu-item"> Direktorat Jenderal Ketenagalistrikan</a>
                    <a href="#" class="submenu-item"> Direktorat Jenderal Mineral Dan Batubara</a>
                    <a href="#" class="submenu-item"> Ditjen Energi Baru, Terbarukan & Konservasi Energi</a>
                    <a href="#" class="submenu-item"> Direktorat Jenderal Penegakan Hukum ESDM</a>
                    <a href="#" class="submenu-item"> Inspektorat Jenderal</a>
                    <a href="#" class="submenu-item"> Badan Geologi</a>
                    <a href="#" class="submenu-item"> Badan Pengembangan SDM ESDM</a>
                    <a href="#" class="submenu-item"> Sekretariat Jenderal Dewan Energi Nasional</a>
                    <a href="#" class="submenu-item"> Badan Pengatur Hilir Minyak Dan Gas Bumi</a>
                </div>
            </div>

            <a href="usulan.php" class="menu-item">
                <div class="menu-item-left">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Usulan Tambah/Edit</span>
                </div>
            </a>
            
            <a href="logout.php" class="menu-item" style="margin-top: 20px; color: #DC2626;">
                <div class="menu-item-left">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Wrapper Kanan -->
    <div class="main-wrapper">
        
        <!-- Top Header Bar (Background Kuning) -->
        <div class="top-header">
            <div class="top-header-right">

                <span>Kelola Peta Jabatan</span>
            </div>
        </div>

        <!-- Container Konten -->
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
                            <td> Badan Pengatur Hilir Minyak Dan Gas Bumi</td>
                            <td class="num-b">700</td>
                            <td class="num-e">697</td>
                            <td class="num-s">-3</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Script JavaScript untuk Toggle Dropdown Sidebar -->
    <script>
        const dropdownBtn = document.getElementById('dropdownBtn');
        const submenuList = document.getElementById('submenuList');
        const arrowIcon = document.getElementById('arrowIcon');

        dropdownBtn.addEventListener('click', function() {
            // Toggle class 'show' pada list submenu
            submenuList.classList.toggle('show');
            
            // Ubah arah panah (chevron-up / chevron-down)
            if (submenuList.classList.contains('show')) {
                arrowIcon.classList.remove('fa-chevron-down');
                arrowIcon.classList.add('fa-chevron-up');
            } else {
                arrowIcon.classList.remove('fa-chevron-up');
                arrowIcon.classList.add('fa-chevron-down');
            }
        });
    </script>

</body>
</html>
