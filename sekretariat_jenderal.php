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
    <title>Sekretariat Jenderal - Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #FFC107; /* Kuning keemasan sesuai referensi */
            --bg-body: #FDFBF7;
            --text-main: #334155;
            --sidebar-width: 330px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); display: flex; min-height: 100vh; color: var(--text-main); }

        /* Sidebar Putih Bersih */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; padding: 25px 20px; display: flex; flex-direction: column; border-right: 1px solid #E2E8F0; position: fixed; height: 100vh; overflow-y: auto; box-shadow: 4px 0 15px rgba(0,0,0,0.04); z-index: 100; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #E2E8F0; color: var(--primary-esdm); }
        .sidebar-brand i { color: #C5A059; font-size: 22px; }
        
        .sidebar ul { list-style: none; padding: 0; flex: 1; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a { color: #64748B; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: 0.2s; white-space: nowrap; }
        .sidebar ul li a:hover { background-color: #F1F5F9; color: var(--primary-esdm); }
        
        /* Submenu */
        .submenu { list-style: none; padding-left: 20px; padding-right: 15px; margin-top: 6px; display: block; border-left: 2px solid #E2E8F0; margin-left: 15px; }
        .submenu li { margin-bottom: 6px; }
        .submenu li a { font-size: 12px; padding: 8px 10px; color: #64748B; font-weight: 600; white-space: normal; line-height: 1.4; display: block; }
        .submenu li a:hover { color: var(--primary-esdm); }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; }
        
        /* Top Header Kuning Keemasan sesuai referensi */
        .top-header { background: var(--accent-gold); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-weight: 700; font-size: 13px; color: var(--primary-esdm); }
        .top-header .badge-ad { width: 32px; height: 32px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }

        /* Content Body */
        .content-body { padding: 30px; }
        
        .breadcrumb-link { font-size: 12px; color: #D97706; text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 10px; }
        .page-title-box { margin-bottom: 25px; }
        .page-title-box h1 { font-size: 22px; font-weight: 800; color: var(--primary-esdm); text-transform: uppercase; letter-spacing: -0.5px; }
        .page-title-box p { font-size: 14px; font-weight: 700; color: #475569; margin-top: 2px; }

        /* Stat Card Item (Panjang mendatar) */
        .stat-card-box { background: white; border: 1px solid #E2E8F0; border-radius: 10px; padding: 18px 25px; margin-bottom: 12px; display: grid; grid-template-columns: 50px 2fr 1fr 1fr 1fr; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
        .stat-icon { width: 38px; height: 38px; background: rgba(255, 193, 7, 0.15); color: #B45309; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; }
        .stat-label { font-size: 13px; font-weight: 800; color: var(--primary-esdm); letter-spacing: 0.5px; }
        .stat-col { text-align: center; }
        .stat-col .label { font-size: 10px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 2px; }
        .stat-col .val-blue { font-size: 16px; font-weight: 800; color: #2563EB; }
        .stat-col .val-green { font-size: 16px; font-weight: 800; color: #059669; }
        .stat-col .val-red { font-size: 16px; font-weight: 800; color: #DC2626; }

        /* Struktur Organisasi Section */
        .org-section { background: white; border: 1px solid #E2E8F0; border-radius: 10px; padding: 25px; margin-top: 25px; margin-bottom: 25px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
        .org-section h3 { font-size: 14px; font-weight: 800; color: var(--primary-esdm); margin-bottom: 20px; }
        
        .org-tree { display: flex; flex-direction: column; align-items: center; gap: 20px; overflow-x: auto; padding-bottom: 10px; }
        .node-parent { background: var(--accent-gold); color: var(--primary-esdm); font-weight: 800; padding: 10px 25px; border-radius: 8px; font-size: 13px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; }
        
        .org-children { display: grid; grid-template-columns: repeat(5, minmax(140px, 1fr)); gap: 12px; width: 100%; margin-top: 15px; }
        .node-child { background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 12px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
        .node-child .title { font-size: 11px; font-weight: 700; color: var(--primary-esdm); margin-bottom: 8px; min-height: 28px; }
        .node-child .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; font-size: 10px; border-top: 1px solid #F1F5F9; padding-top: 6px; }
        .node-child .stats div { display: flex; flex-direction: column; align-items: center; font-weight: 700; }
        .node-child .stats span:first-child { font-size: 9px; color: #64748B; }

        /* Tabel Rangkuman Unit Eselon II */
        .table-section { background: white; border: 1px solid #E2E8F0; border-radius: 10px; padding: 25px; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
        .table-section h3 { background: var(--accent-gold); color: var(--primary-esdm); padding: 12px 15px; border-radius: 6px; font-size: 14px; font-weight: 800; margin-bottom: 15px; }
        
        .table-esdm { width: 100%; border-collapse: collapse; font-size: 12px; }
        .table-esdm th, .table-esdm td { padding: 12px 15px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .table-esdm th { background: #F8FAFC; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; }
        .table-esdm td { font-weight: 600; color: #334155; }
        .table-esdm td.num-blue { color: #2563EB; text-align: center; }
        .table-esdm td.num-green { color: #059669; text-align: center; }
        .table-esdm td.num-red { color: #DC2626; text-align: center; font-weight: 800; }
        .table-esdm th:nth-child(2), .table-esdm th:nth-child(3), .table-esdm th:nth-child(4) { text-align: center; }
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
            <li>
                <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            </li>
            
            <li class="has-submenu" style="position: relative;">
                <div style="display: flex; align-items: center; border-radius: 8px; overflow: hidden; background: #F1F5F9;">
                    <a href="kelola_peta_jabatan.php" style="flex: 1; color: var(--primary-esdm); text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; font-size: 14px; font-weight: 700;">
                        <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan
                    </a>
                    <span id="btn-toggle-dropdown" style="padding: 12px 15px; cursor: pointer; color: var(--primary-esdm); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-chevron-up arrow-icon" style="font-size: 11px;"></i>
                    </span>
                </div>
                <ul class="submenu" id="submenu-peta" style="display: block;">
                    <li><a href="sekretariat_jenderal.php" style="color: var(--primary-esdm); font-weight: 700;">1. Sekretariat Jenderal</a></li>
                    <li><a href="#">2. Direktorat Jenderal Minyak dan Gas Bumi</a></li>
                    <li><a href="#">3. Direktorat Jenderal Ketenagalistrikan</a></li>
                    <li><a href="#">4. Direktorat Jenderal Mineral Dan Batubara</a></li>
                    <li><a href="#">5. Ditjen EBTKE</a></li>
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
        <!-- Top Header Kuning Sesuai Referensi -->
        <div class="top-header">
            <span>Kementerian ESDM &nbsp;&gt;&nbsp; 1. Sekretariat Jenderal</span>
            <div class="badge-ad">AD</div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            <a href="kelola_peta_jabatan.php" class="breadcrumb-link"><i class="fa-solid fa-arrow-left"></i> &nbsp;Kembali ke Pusat</a>
            
            <div class="page-title-box">
                <p>PETA JABATAN</p>
                <h1>1. Sekretariat Jenderal</h1>
            </div>

            <!-- Kartu 1: Total Pegawai -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-label">TOTAL PEGAWAI</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">820</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">765</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-55</div></div>
            </div>

            <!-- Kartu 2: Jabatan Struktural -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div class="stat-label">JABATAN STRUKTURAL</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">41</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">40</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-1</div></div>
            </div>

            <!-- Kartu 3: Jab. Admin / Pengawas -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-gear"></i></div>
                <div class="stat-label">JAB. ADMIN / PENGAWAS</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">82</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">73</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-8</div></div>
            </div>

            <!-- Kartu 4: Jabatan Fungsional -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="stat-label">JABATAN FUNGSIONAL</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">492</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">451</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-41</div></div>
            </div>

            <!-- Kartu 5: Jabatan Pelaksana -->
            <div class="stat-card-box">
                <div class="stat-icon"><i class="fa-solid fa-user-shield"></i></div>
                <div class="stat-label">JABATAN PELAKSANA</div>
                <div class="stat-col"><div class="label">Kebutuhan</div><div class="val-blue">205</div></div>
                <div class="stat-col"><div class="label">Eksisting</div><div class="val-green">188</div></div>
                <div class="stat-col"><div class="label">Selisih</div><div class="val-red">-16</div></div>
            </div>

            <!-- Bagan Struktur Organisasi Setjen -->
            <div class="org-section">
                <h3>Struktur Organisasi 1. Sekretariat Jenderal</h3>
                <div class="org-tree">
                    <div class="node-parent">1. Sekretariat Jenderal</div>
                    <div class="org-children">
                        <div class="node-child"><div class="title">Biro Perencanaan</div><div class="stats"><div><span>KEB</span><span style="color:#2563EB">142</span></div><div><span>EKS</span><span style="color:#059669">120</span></div><div><span>SEL</span><span style="color:#DC2626">-22</span></div></div></div>
                        <div class="node-child"><div class="title">Biro Organisasi & SDM</div><div class="stats"><div><span>KEB</span><span style="color:#2563EB">95</span></div><div><span>EKS</span><span style="color:#059669">88</span></div><div><span>SEL</span><span style="color:#DC2626">-7</span></div></div></div>
                        <div class="node-child"><div class="title">Biro Keuangan</div><div class="stats"><div><span>KEB</span><span style="color:#2563EB">80</span></div><div><span>EKS</span><span style="color:#059669">75</span></div><div><span>SEL</span><span style="color:#DC2626">-5</span></div></div></div>
                        <div class="node-child"><div class="title">Biro Hukum</div><div class="stats"><div><span>KEB</span><span style="color:#2563EB">65</span></div><div><span>EKS</span><span style="color:#059669">60</span></div><div><span>SEL</span><span style="color:#DC2626">-5</span></div></div></div>
                        <div class="node-child"><div class="title">Biro Adm. Pimpinan</div><div class="stats"><div><span>KEB</span><span style="color:#2563EB">85</span></div><div><span>EKS</span><span style="color:#059669">80</span></div><div><span>SEL</span><span style="color:#DC2626">-5</span></div></div></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Rangkuman Unit Eselon II -->
            <div class="table-section">
                <h3>Rangkuman Unit Eselon II</h3>
                <table class="table-esdm">
                    <thead>
                        <tr>
                            <th>Nama Unit Kerja Eselon II</th>
                            <th>Kebutuhan</th>
                            <th>Eksisting</th>
                            <th>Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1. Biro Perencanaan</td><td class="num-blue">142</td><td class="num-green">120</td><td class="num-red">-22</td></tr>
                        <tr><td>2. Biro Organisasi dan Sumber Daya Manusia</td><td class="num-blue">95</td><td class="num-green">88</td><td class="num-red">-7</td></tr>
                        <tr><td>3. Biro Keuangan</td><td class="num-blue">80</td><td class="num-green">75</td><td class="num-red">-5</td></tr>
                        <tr><td>4. Biro Hukum</td><td class="num-blue">65</td><td class="num-green">60</td><td class="num-red">-5</td></tr>
                        <tr><td>5. Biro Administrasi Pimpinan Dan Protokol</td><td class="num-blue">85</td><td class="num-green">80</td><td class="num-red">-5</td></tr>
                        <tr><td>6. Biro Komunikasi</td><td class="num-blue">75</td><td class="num-green">70</td><td class="num-red">-5</td></tr>
                        <tr><td>7. Biro Umum</td><td class="num-blue">150</td><td class="num-green">145</td><td class="num-red">-5</td></tr>
                        <tr><td>8. PPSMN</td><td class="num-blue">50</td><td class="num-green">48</td><td class="num-red">-2</td></tr>
                        <tr><td>9. PUSDATIN</td><td class="num-blue">85</td><td class="num-green">75</td><td class="num-red">-10</td></tr>
                        <tr><td>10. PUSAKA</td><td class="num-blue">45</td><td class="num-green">40</td><td class="num-red">-5</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script Dropdown -->
    <script>
        const btnToggle = document.getElementById('btn-toggle-dropdown');
        const submenu = document.getElementById('submenu-peta');
        const arrowIcon = btnToggle.querySelector('.arrow-icon');

        btnToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                arrowIcon.classList.remove('fa-chevron-down');
                arrowIcon.classList.add('fa-chevron-**Bisa banget!** Saya sangat paham dan bisa membuatkan halaman **Sekretariat Jenderal** yang persis seperti gambar tersebut.

Berdasarkan gambar referensi yang kamu berikan, halaman Sekretariat Jenderal ini memiliki struktur yang sangat rapi dan profesional, terdiri dari:
1. **Header Atas Berwarna Kuning Emas** dengan *breadcrumb* (`Kementerian ESDM > 1. Sekretariat Jenderal`) dan tombol akun `AD` di kanan.
2. **Judul Halaman Utama** (`PETA JABATAN - 1. Sekretariat Jenderal`).
3. **5 Baris Kartu Statistik Utama** (Total Pegawai, Jabatan Struktural, Jab. Admin/Pengawas, Jabatan Fungsional, Jabatan Pelaksana) yang menampilkan Kebutuhan, Eksisting, dan Selisih.
4. **Bagan Struktur Organisasi Eselon II** di tengah (menampilkan hierarki dari Sekjen ke biro-biro di bawahnya seperti Biro Perencanaan, Biro Organisasi dan SDM, Biro Keuangan, Biro Hukum, Biro Administrasi Pimpinan, Biro Komunikasi, Biro Umum, PPSDM, PUSDATIN, dan PUSAKA).
5. **Tabel Rangkuman Unit Eselon II** di bagian bawah yang merinci data Kebutuhan, Eksisting, dan Selisih untuk masing-masing Biro.

Berikut adalah kode lengkap untuk file **`sekretariat_jenderal.php`** yang sudah dirancang presisi sesuai gambar tersebut:

```php
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
    <link href="[https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap)" rel="stylesheet">
    <link rel="stylesheet" href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css)">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --accent-gold: #F5C518; /* Kuning emas khas ESDM */
            --bg-body: #FDFDFD;
            --text-main: #222222;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); display: flex; flex-direction: column; min-height: 100vh; }

        /* Header Kuning Atas */
        .top-banner { background-color: var(--accent-gold); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 13px; color: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .user-badge { width: 32px; height: 32px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }

        /* Content Container */
        .container { padding: 30px 40px; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* Breadcrumb & Title */
        .back-link { font-size: 12px; color: #555; text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 8px; }
        .back-link:hover { text-decoration: underline; }
        .page-title { font-size: 22px; font-weight: 800; color: #000; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px; }

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

        /* Struktur Organisasi Section */
        .org-section { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 8px; padding: 25px; margin-top: 30px; margin-bottom: 30px; }
        .org-section h4 { font-size: 13px; font-weight: 700; color: #64748B; margin-bottom: 20px; }
        
        .org-tree { display: flex; flex-direction: column; align-items: center; position: relative; }
        .parent-node { background: var(--accent-gold); color: #000; font-weight: 800; font-size: 12px; padding: 10px 25px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #EAB308; z-index: 2; margin-bottom: 30px; }
        
        /* Garis Penghubung Organisasi */
        .org-tree::after { content: ''; position: absolute; top: 35px; width: 85%; height: 2px; background: var(--accent-gold); z-index: 1; }

        .children-nodes { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; z-index: 2; width: 100%; }
        .child-card { background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; padding: 10px; width: 115px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .child-card .unit-name { font-size: 10px; font-weight: 700; color: #1E293B; height: 32px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; line-height: 1.2; }
        .child-stats { display: flex; justify-content: space-around; border-top: 1px solid #F1F5F9; padding-top: 6px; font-size: 9px; font-weight: 700; }
        .child-stats .c-keb { color: #2563EB; }
        .child-stats .c-eks { color: #059669; }
        .child-stats .c-sel { color: #DC2626; }

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
    <div class="top-banner">
        <span>Kementerian ESDM &nbsp;&gt;&nbsp; 1. Sekretariat Jenderal</span>
        <div class="user-badge">AD</div>
    </div>

    <!-- Main Container -->
    <div class="container">
        
        <!-- Breadcrumb & Judul -->
        <a href="kelola_peta_jabatan.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pusat</a>
        <div class="page-title">Peta Jabatan<br><span style="font-size: 16px; font-weight: 600; color: #475569;">1. Sekretariat Jenderal</span></div>

        <!-- Kartu 1: Total Pegawai -->
        <div class="stat-card-row">
            <div class="stat-left">
                <div class="stat-icon-box"><i class="fa-solid fa-users"></i></div>
                <div class="stat-label-text">TOTAL PEGAWAI</div>
            </div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">820</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">765</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-55</span></div>
            </div>
        </div>

        <!-- Kartu 2: Jabatan Struktural -->
        <div class="stat-card-row">
            <div class="stat-left">
                <div class="stat-icon-box"><i class="fa-solid fa-user-tie"></i></div>
                <div class="stat-label-text">JABATAN STRUKTURAL</div>
            </div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">41</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">40</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-1</span></div>
            </div>
        </div>

        <!-- Kartu 3: Jab. Admin / Pengawas -->
        <div class="stat-card-row">
            <div class="stat-left">
                <div class="stat-icon-box"><i class="fa-solid fa-user-gear"></i></div>
                <div class="stat-label-text">JAB. ADMIN / PENGAWAS</div>
            </div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">82</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">73</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-8</span></div>
            </div>
        </div>

        <!-- Kartu 4: Jabatan Fungsional -->
        <div class="stat-card-row">
            <div class="stat-left">
                <div class="stat-icon-box"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="stat-label-text">JABATAN FUNGSIONAL</div>
            </div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">492</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">451</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-41</span></div>
            </div>
        </div>

        <!-- Kartu 5: Jabatan Pelaksana -->
        <div class="stat-card-row">
            <div class="stat-left">
                <div class="stat-icon-box"><i class="fa-solid fa-user-shield"></i></div>
                <div class="stat-label-text">JABATAN PELAKSANA</div>
            </div>
            <div class="stat-right-values">
                <div class="stat-val-item"><span class="title">KEBUTUHAN</span><span class="number-blue">205</span></div>
                <div class="stat-val-item"><span class="title">EKSISTING</span><span class="number-green">188</span></div>
                <div class="stat-val-item"><span class="title">SELISIH</span><span class="number-red">-16</span></div>
            </div>
        </div>

        <!-- Struktur Organisasi Eselon II -->
        <div class="org-section">
            <h4>Struktur Organisasi 1. Sekretariat Jenderal</h4>
            <div class="org-tree">
                <div class="parent-node">1. Sekretariat Jenderal</div>
                <div class="children-nodes">
                    <!-- Biro 1 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Perencanaan</div>
                        <div class="child-stats">
                            <span class="c-keb">142</span>
                            <span class="c-eks">120</span>
                            <span class="c-sel">-22</span>
                        </div>
                    </div>
                    <!-- Biro 2 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Organisasi dan SDM</div>
                        <div class="child-stats">
                            <span class="c-keb">95</span>
                            <span class="c-eks">88</span>
                            <span class="c-sel">-7</span>
                        </div>
                    </div>
                    <!-- Biro 3 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Keuangan</div>
                        <div class="child-stats">
                            <span class="c-keb">80</span>
                            <span class="c-eks">75</span>
                            <span class="c-sel">-5</span>
                        </div>
                    </div>
                    <!-- Biro 4 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Hukum</div>
                        <div class="child-stats">
                            <span class="c-keb">65</span>
                            <span class="c-eks">60</span>
                            <span class="c-sel">-5</span>
                        </div>
                    </div>
                    <!-- Biro 5 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Administrasi Pimpinan</div>
                        <div class="child-stats">
                            <span class="c-keb">85</span>
                            <span class="c-eks">80</span>
                            <span class="c-sel">-5</span>
                        </div>
                    </div>
                    <!-- Biro 6 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Komunikasi</div>
                        <div class="child-stats">
                            <span class="c-keb">75</span>
                            <span class="c-eks">70</span>
                            <span class="c-sel">-5</span>
                        </div>
                    </div>
                    <!-- Biro 7 -->
                    <div class="child-card">
                        <div class="unit-name">Biro Umum</div>
                        <div class="child-stats">
                            <span class="c-keb">150</span>
                            <span class="c-eks">145</span>
                            <span class="c-sel">-5</span>
                        </div>
                    </div>
                    <!-- Biro 8 -->
                    <div class="child-card">
                        <div class="unit-name">PPSDM</div>
                        <div class="child-stats">
                            <span class="c-keb">50</span>
                            <span class="c-eks">48</span>
                            <span class="c-sel">-2</span>
                        </div>
                    </div>
                    <!-- Biro 9 -->
                    <div class="child-card">
                        <div class="unit-name">PUSDATIN</div>
                        <div class="child-stats">
                            <span class="c-keb">85</span>
                            <span class="c-eks">75</span>
                            <span class="c-sel">-10</span>
                        </div>
                    </div>
                    <!-- Biro 10 -->
                    <div class="child-card">
                        <div class="unit-name">PUSAKA</div>
                        <div class="child-stats">
                            <span class="c-keb">45</span>
                            <span class="c-eks">40</span>
                            <span class="c-sel">-5</span>
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
