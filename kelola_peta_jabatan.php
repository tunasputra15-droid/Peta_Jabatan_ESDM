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
    <title>Kelola Peta Jabatan - Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;
            --bg-body: #E0F2FE; /* Background biru bawaan aplikasi kita */
            --text-main: #334155;
            --sidebar-width: 260px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); display: flex; min-height: 100vh; color: var(--text-main); }

        /* Sidebar Putih Bersih sesuai bawaan */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; padding: 25px 20px; display: flex; flex-direction: column; border-right: 1px solid #E2E8F0; position: fixed; height: 100vh; overflow-y: auto; box-shadow: 4px 0 15px rgba(0,0,0,0.04); }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #E2E8F0; color: var(--primary-esdm); }
        .sidebar-brand i { color: var(--accent-gold); font-size: 22px; }
        
        .sidebar ul { list-style: none; padding: 0; flex: 1; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a { color: #64748B; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: 0.2s; }
        .sidebar ul li a:hover { background-color: #F1F5F9; color: var(--primary-esdm); }
        
        /* Dropdown Styling */
        .dropdown-toggle { background: var(--primary-esdm); color: white !important; font-weight: 700 !important; }
        .dropdown-toggle i { color: var(--accent-gold) !important; }
        .submenu { list-style: none; padding-left: 20px; margin-top: 6px; display: block; border-left: 2px solid #E2E8F0; margin-left: 15px; }
        .submenu li a { font-size: 13px; padding: 8px 10px; color: #64748B; font-weight: 600; }
        .submenu li a:hover, .submenu li a.active-sub { color: var(--primary-esdm); }

        /* Main Content Area */
        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; }
        
        /* Top Header Kuning Kementerian */
        .top-header { background: #FACC15; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .top-header span { font-size: 14px; font-weight: 700; color: var(--primary-esdm); }
        .user-avatar { background: var(--primary-esdm); color: #FACC15; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }

        /* Content Body */
        .content-body { padding: 30px; }
        .page-title-box { margin-bottom: 25px; }
        .page-title-box h1 { font-size: 24px; font-weight: 800; color: var(--primary-esdm); letter-spacing: -0.5px; margin-bottom: 5px; }
        .page-title-box p { font-size: 13px; color: #475569; font-weight: 500; }

        /* Stat Card Item */
        .stat-card-box { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px 25px; margin-bottom: 15px; display: grid; grid-template-columns: 50px 1.5fr 1fr 1fr 1fr; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .stat-icon { width: 40px; height: 40px; background: rgba(197, 160, 89, 0.15); color: var(--accent-gold); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .stat-label { font-size: 13px; font-weight: 800; color: var(--primary-esdm); letter-spacing: 0.5px; }
        .stat-col { text-align: center; }
        .stat-col .label { font-size: 10px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 4px; }
        .stat-col .val-blue { font-size: 18px; font-weight: 800; color: #2563EB; }
        .stat-col .val-green { font-size: 18px; font-weight: 800; color: #059669; }
        .stat-col .val-red { font-size: 18px; font-weight: 800; color: #DC2626; }

        /* Tabel Rangkuman Unit Eselon I */
        .table-section { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 25px; margin-top: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .table-section h3 { font-size: 16px; font-weight: 800; color: var(--primary-esdm); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #E2E8F0; }
        
        .table-esdm { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table-esdm th, .table-esdm td { padding: 14px 15px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .table-esdm th { background: #F8FAFC; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .table-esdm td { font-weight: 600; color: #334155; }
        .table-esdm td.num-blue { color: #2563EB; text-align: center; }
        .table-esdm td.num-green { color: #059669; text-align: center; }
        .table-esdm td.num-red { color: #DC2626; text-align: center; font-weight: 800; }
        .table-esdm th:nth-child(2), .table-esdm th:nth-child(3), .table-esdm th:nth-child(4) { text-align: center; }
    </style>
</head>
<body>

    <!-- Sidebar Putih Bersih -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-building-shield"></i>
            <span>ADMIN UNIT ESDM</span>
        </div>
        <ul>
            <li>
                <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            </li>
            
            <li class="has-submenu">
             <a href="kelola_peta_jabatan.php" class="dropdown-toggle" id="menu-peta">
             <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan 
             <i class="fa-solid fa-chevron-down arrow-icon" style="margin-left: auto; font-size: 11px;"></i>
             </a>
                <ul class="submenu" id="submenu-peta">
                    <li><a href="sekretariat_jenderal.php">Sekretariat Jenderal</a></li>
                    <li><a href="#">Direktorat Jenderal Minyak dan Gas Bumi</a></li>
                    <li><a href="#">Direktorat Jenderal Ketenagalistrikan</a></li>
                    <li><a href="#">Direktorat Jenderal Mineral Dan Batubara</a></li>
                    <li><a href="#">Ditjen EBTKE</a></li>
                </ul>
            </li>

            <li>
                <a href="#"><i class="fa-solid fa-pen-to-square"></i> Usulan Tambah/Edit</a>
            </li>

            <li style="margin-top: 20px;">
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header Kuning -->
        <div class="top-header">
            <span>Kementerian ESDM</span>
            <div class="user-avatar">AD</div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            <div class="page-title-box">
                <h1>PETA JABATAN KEMENTERIAN ESDM</h1>
                <p>Rekapitulasi Kebutuhan dan Eksisting Pegawai Tingkat Kementerian</p>
            </div>

            <!-- Kartu 1: Total Pegawai -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-label">TOTAL PEGAWAI</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">6240</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">5892</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-348</div></div>
            </div>

            <!-- Kartu 2: Jabatan Struktural -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div class="stat-label">JABATAN STRUKTURAL</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">125</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">118</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-7</div></div>
            </div>

            <!-- Kartu 3: Jab. Admin / Pengawas -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-gear"></i></div>
                <div class="stat-label">JAB. ADMIN / PENGAWAS</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">450</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">412</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-38</div></div>
            </div>

            <!-- Kartu 4: Jabatan Fungsional -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="stat-label">JABATAN FUNGSIONAL</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">4120</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">3950</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-170</div></div>
            </div>

            <!-- Kartu 5: Jabatan Pelaksana -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-shield"></i></div>
                <div class="stat-label">JABATAN PELAKSANA</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">1545</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">1412</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-133</div></div>
            </div>

            <!-- Tabel Rangkuman Unit Eselon I (1-11) -->
            <div class="table-section">
                <h3>Rangkuman Unit Eselon I</h3>
                <table class="table-esdm">
                    <thead>
                        <tr>
                            <th>Nama Unit Kerja Eselon I</th>
                            <th>Kebutuhan</th>
                            <th>Eksisting</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1. Sekretariat Jenderal</td><td class="num-blue">820</td><td class="num-green">765</td><td class="num-red">-55</td></tr>
                        <tr><td>2. Direktorat Jenderal Minyak dan Gas Bumi</td><td class="num-blue">600</td><td class="num-green">550</td><td class="num-red">-50</td></tr>
                        <tr><td>3. Direktorat Jenderal Ketenagalistrikan</td><td class="num-blue">450</td><td class="num-green">420</td><td class="num-red">-30</td></tr>
                        <tr><td>4. Direktorat Jenderal Mineral Dan Batubara</td><td class="num-blue">700</td><td class="num-green">680</td><td class="num-red">-20</td></tr>
                        <tr><td>5. Ditjen Energi Baru, Terbarukan & Konservasi Energi</td><td class="num-blue">500</td><td class="num-green">470</td><td class="num-red">-30</td></tr>
                        <tr><td>6. Direktorat Jenderal Penegakan Hukum ESDM</td><td class="num-blue">400</td><td class="num-green">350</td><td class="num-red">-50</td></tr>
                        <tr><td>7. Inspektorat Jenderal</td><td class="num-blue">350</td><td class="num-green">340</td><td class="num-red">-10</td></tr>
                        <tr><td>8. Badan Geologi</td><td class="num-blue">850</td><td class="num-green">800</td><td class="num-red">-50</td></tr>
                        <tr><td>9. Badan Pengembangan SDM ESDM</td><td class="num-blue">650</td><td class="num-green">620</td><td class="num-red">-30</td></tr>
                        <tr><td>10. Sekretariat Jenderal Dewan Energi Nasional</td><td class="num-blue">220</td><td class="num-green">200</td><td class="num-red">-20</td></tr>
                        <tr><td>11. Badan Pengatur Hilir Minyak Dan Gas Bumi</td><td class="num-blue">700</td><td class="num-green">697</td><td class="num-red">-3</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script Dropdown Naik Turun -->
    <script>
        const dropdownToggle = document.getElementById('menu-peta');
        const submenu = document.getElementById('submenu-peta');
        const arrowIcon = dropdownToggle.querySelector('.arrow-icon');

        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                arrowIcon.classList.remove('fa-chevron-down');
                arrowIcon.classList.add('fa-chevron-up');
            } else {
                submenu.style.display = 'none';
                arrowIcon.classList.remove('fa-chevron-up');
                arrowIcon.classList.add('fa-chevron-down');
            }
        });
    </script>
</body>
</html>
