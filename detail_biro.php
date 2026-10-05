<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$biro_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$daftar_biro = [
    1 => "Biro Perencanaan",
    2 => "Biro Organisasi & SDM",
    3 => "Biro Keuangan",
    4 => "Biro Hukum",
    5 => "Biro Adm. Pimpinan",
    6 => "Biro Komunikasi",
    7 => "Biro Umum",
    8 => "PPSDM",
    9 => "PUSDATIN",
    10 => "PUSAKA"
];

$nama_biro = isset($daftar_biro[$biro_id]) ? $daftar_biro[$biro_id] : "Biro Perencanaan";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peta Jabatan - <?php echo $nama_biro; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --accent-gold: #FFC107;
            --bg-body: #F4F6F9;
            --text-main: #334155;
            --sidebar-width: 290px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; }

        /* --- SIDEBAR (Diselaraskan persis dengan standar Gambar 2) --- */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; border-right: 1px solid #E2E8F0; display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100; overflow-y: auto; }
        .sidebar-brand { padding: 20px 24px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; font-weight: 800; color: var(--primary-esdm); }
        .sidebar-brand i { font-size: 16px; color: #D97706; background: #FEF9C3; padding: 8px; border-radius: 6px; }

        .sidebar-menu { padding: 20px 16px; display: flex; flex-direction: column; gap: 4px; flex-grow: 1; }
        .menu-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 12.5px; font-weight: 600; color: #64748B; transition: 0.2s; cursor: pointer; }
        .menu-item-left { display: flex; align-items: center; gap: 10px; white-space: nowrap; }
        .menu-item i { font-size: 14px; width: 20px; text-align: center; }
        
        .menu-item:hover { background: #F8FAFC; color: var(--primary-esdm); }
        .menu-item.dropdown-toggle { background: transparent; color: #64748B; }

        .submenu-container { display: none; flex-direction: column; padding-left: 35px; margin-top: 5px; gap: 4px; }
        .submenu-container.show { display: flex; }
        
        /* Teks sub-menu diselaraskan ukurannya dengan menu utama */
        .submenu-item { padding: 6px 25px; border-radius: 10px; text-decoration: none; font-size: 11px; font-weight: 600; color: #64748B; transition: 0.2s; line-height: 1.1; }
        .submenu-item:hover { background: #F8FAFC; color: var(--primary-esdm); }
        .submenu-item.sub-active { color: #92400E; font-weight: 700; background: #FEF3C7; }

        /* --- MAIN WRAPPER KANAN --- */
        .main-wrapper { 
            margin-left: var(--sidebar-width) !important; 
            width: calc(100% - var(--sidebar-width)) !important; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            background-color: var(--bg-body); 
        }

        .top-header { background: var(--accent-gold); color: var(--primary-esdm); padding: 16px 25px; font-size: 15px; font-weight: 800; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%; }
        .top-header-right { display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 700; color: var(--primary-esdm); }
        .user-avatar { width: 30px; height: 30px; background: var(--primary-esdm); color: var(--accent-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 11px; }

        .container { padding: 20px 25px !important; width: 100% !important; max-width: 100% !important; }

        .back-link { font-size: 11px; color: #D97706; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 10px; }
        .back-link:hover { text-decoration: underline; }

        .detail-top-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 1.5px solid #CBD5E1; padding-bottom: 15px; }
        .detail-title-area .page-title { font-size: 20px; font-weight: 800; color: #000; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .detail-title-area .page-subtitle { font-size: 13px; font-weight: 700; color: #1E293B; }

        .summary-totals { display: flex; gap: 10px; }
        .sum-box { background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; padding: 8px 14px; text-align: center; min-width: 95px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .sum-box .s-label { font-size: 7.5px; font-weight: 700; color: #64748B; text-transform: uppercase; margin-bottom: 3px; display: block; }
        .sum-box .s-val-b { font-size: 13px; font-weight: 800; color: #2563EB; }
        .sum-box .s-val-e { font-size: 13px; font-weight: 800; color: #059669; }
        .sum-box .s-val-s { font-size: 13px; font-weight: 800; color: #DC2626; }

        .stat-cards-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px; }
        .stat-box-item { background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 8px; padding: 14px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .stat-box-top { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: 11px; font-weight: 800; color: #0F172A; }
        .stat-box-top i { background: #FEF9C3; color: #854D0E; border: 1px solid #FDE047; padding: 6px; border-radius: 5px; font-size: 11px; }
        .stat-box-nums { display: flex; justify-content: space-around; text-align: center; border-top: 1px solid #F1F5F9; padding-top: 8px; font-size: 9px; }
        .stat-box-nums .sb-col { flex: 1; border-right: 1px solid #E2E8F0; }
        .stat-box-nums .sb-col:last-child { border-right: none; }
        .stat-box-nums .sb-lbl { color: #64748B; font-weight: 700; margin-bottom: 2px; display: block; font-size: 8px; }
        .stat-box-nums .sb-num-b { color: #2563EB; font-size: 12px; font-weight: 800; }
        .stat-box-nums .sb-num-e { color: #059669; font-size: 12px; font-weight: 800; }
        .stat-box-nums .sb-num-s { color: #DC2626; font-size: 12px; font-weight: 800; }

        .org-detail-section { background: #FFFFFF; border: 1.5px solid #FDE047; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.03); }
        .org-detail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #E2E8F0; padding-bottom: 12px; }
        .org-detail-header .od-title { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: #1E293B; }
        .org-detail-header .od-title i { color: #D97706; background: #FEF9C3; padding: 6px; border-radius: 5px; }
        .format-badge { background: #FEF9C3; border: 1px solid #FDE047; color: #854D0E; font-size: 9.5px; font-weight: 700; padding: 5px 12px; border-radius: 4px; }
        .format-badge span { color: #059669; text-decoration: underline; }

        /* --- PERBAIKAN GARIS BAGAN (Gambar 1) --- */
        .tree-container { display: flex; flex-direction: column; align-items: center; width: 100%; padding-bottom: 15px; overflow-x: auto; }
        
        .root-node { 
            background: #FFC107; 
            color: #000000; 
            text-align: center; 
            padding: 19px 35px; 
            border-radius: 8px; 
            font-weight: 800; 
            font-size: 12.5px; 
            box-shadow: 0 3px 6px rgba(0,0,0,0.08); 
            border: 2px solid #EAB308; 
            margin-bottom: 25px; 
            position: relative;
            z-index: 2;
        }
        .root-node .sub-cls { font-size: 9.5px; font-weight: 700; color: #475569; margin-top: 2px; }
        
        /* Garis vertikal pas di tengah bawah kotak kepala biro */
        .root-node::after {
            content: '';
            position: absolute;
            bottom: -28px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 26px;
            background-color: #D97706;
        }

        /* Garis horizontal penghubung cabang agar tersambung simetris */
            .tree-line-h {
            width: 110%; /* Ubah angka ini kalau mau lebih panjang atau pendek */
            height: 3px;
            background-color: #D97706;
            margin: 0 auto 0px ;
            position: relative;
            left: 10px; /* Tambahkan baris ini kalau garisnya mau digeser sedikit ke kiri/kanan agar pas di tengah */
        }

        .branches-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; width: 100%; z-index: 2; }
        
        .branch-column { display: flex; flex-direction: column; align-items: center; position: relative; padding-top: 20px; }
        
        /* Garis vertikal kecil turun ke masing-masing judul cabang */
        .branch-column::before {
            content: '';
            position: absolute;
            top: 0;
            left: 38%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background-color: #D97706;
        }

        .branch-title-card {
            background: #FFC107;
            color: #000;
            font-weight: 800;
            font-size: 11px;
            padding: 8px 16px;
            border-radius: 6px;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #EAB308;
            margin-bottom: 15px;
        }
        .branch-title-card .b-cls { font-size: 8.5px; font-weight: 700; color: #475569; margin-top: 2px; }

        .job-item-card {
            background: #FFFFFF;
            border: 2px solid #FFC107;
            border-radius: 8px;
            padding: 10px 14px;
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            font-size: 11px;
            font-weight: 800;
            color: #0A192F;
        }
        .job-meta {
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 5px;
            padding: 4px 10px;
            font-size: 9.5px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
        }
        .job-meta span { color: #059669; background: #FEF9C3; padding: 1px 5px; border-radius: 3px; border: 1px solid #FDE047; font-weight: 800; }
    </style>
</head>
<body>

    <!-- Sidebar Kiri -->
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
            
            <div>
                <a href="kelola_peta_jabatan.php" class="menu-item dropdown-toggle" id="dropdownBtn" style="text-decoration: none;">
                    <div class="menu-item-left">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>Kelola Peta Jabatan</span>
                    </div>
                    <i class="fa-solid fa-chevron-up" id="arrowIcon" style="font-size: 11px;"></i>
                </a>
                
                <div class="submenu-container show" id="submenuList">
                    <a href="sekretariat_jenderal.php" class="submenu-item sub-active">Sekretariat Jenderal</a>
                    <a href="#" class="submenu-item">Direktorat Jenderal Minyak dan Gas Bumi</a>
                    <a href="#" class="submenu-item">Direktorat Jenderal Ketenagalistrikan</a>
                    <a href="#" class="submenu-item">Direktorat Jenderal Mineral Dan Batubara</a>
                    <a href="#" class="submenu-item">Ditjen Energi Baru, Terbarukan & Konservasi Energi</a>
                    <a href="#" class="submenu-item">Direktorat Jenderal Penegakan Hukum ESDM</a>
                    <a href="#" class="submenu-item">Inspektorat Jenderal</a>
                    <a href="#" class="submenu-item">Badan Geologi</a>
                    <a href="#" class="submenu-item">Badan Pengembangan SDM ESDM</a>
                    <a href="#" class="submenu-item">Sekretariat Jenderal Dewan Energi Nasional</a>
                    <a href="#" class="submenu-item">Badan Pengatur Hilir Minyak Dan Gas Bumi</a>
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
        <div class="top-header">
            <span>Detail Peta Jabatan <?php echo $nama_biro; ?></span>
            <div class="top-header-right">
            </div>
        </div>

        <div class="container"> 
            <div class="detail-top-row">
                <div class="detail-title-area">
                    <div class="page-title">DETAIL PETA JABATAN</div>
                </div>
                <div class="summary-totals">
                    <div class="sum-box"><span class="s-label">Total Kebutuhan</span><span class="s-val-b">142</span></div>
                    <div class="sum-box"><span class="s-label">Total Eksisting</span><span class="s-val-e">120</span></div>
                    <div class="sum-box"><span class="s-label">Total Selisih</span><span class="s-val-s">-22</span></div>
                </div>
            </div>

            <!-- 4 Kartu Statistik -->
            <div class="stat-cards-grid">
                <div class="stat-box-item">
                    <div class="stat-box-top"><i class="fa-solid fa-user-tie"></i> JABATAN STRUKTURAL</div>
                    <div class="stat-box-nums">
                        <div class="sb-col"><span class="sb-lbl">KEB</span><span class="sb-num-b">7</span></div>
                        <div class="sb-col"><span class="sb-lbl">EKS</span><span class="sb-num-e">7</span></div>
                        <div class="sb-col"><span class="sb-lbl">SEL</span><span class="sb-num-s">0</span></div>
                    </div>
                </div>
                <div class="stat-box-item">
                    <div class="stat-box-top"><i class="fa-solid fa-user-gear"></i> JAB. ADMIN / PENGAWAS</div>
                    <div class="stat-box-nums">
                        <div class="sb-col"><span class="sb-lbl">KEB</span><span class="sb-num-b">14</span></div>
                        <div class="sb-col"><span class="sb-lbl">EKS</span><span class="sb-num-e">11</span></div>
                        <div class="sb-col"><span class="sb-lbl">SEL</span><span class="sb-num-s">-3</span></div>
                    </div>
                </div>
                <div class="stat-box-item">
                    <div class="stat-box-top"><i class="fa-solid fa-graduation-cap"></i> JABATAN FUNGSIONAL</div>
                    <div class="stat-box-nums">
                        <div class="sb-col"><span class="sb-lbl">KEB</span><span class="sb-num-b">85</span></div>
                        <div class="sb-col"><span class="sb-lbl">EKS</span><span class="sb-num-e">78</span></div>
                        <div class="sb-col"><span class="sb-lbl">SEL</span><span class="sb-num-s">-7</span></div>
                    </div>
                </div>
                <div class="stat-box-item">
                    <div class="stat-box-top"><i class="fa-solid fa-user-shield"></i> JABATAN PELAKSANA</div>
                    <div class="stat-box-nums">
                        <div class="sb-col"><span class="sb-lbl">KEB</span><span class="sb-num-b">35</span></div>
                        <div class="sb-col"><span class="sb-lbl">EKS</span><span class="sb-num-e">31</span></div>
                        <div class="sb-col"><span class="sb-lbl">SEL</span><span class="sb-num-s">-4</span></div>
                    </div>
                </div>
            </div>

            <!-- Struktur Peta Jabatan -->
            <div class="org-detail-section">
                <div class="org-detail-header">
                    <div class="od-title"><i class="fa-solid fa-sitemap"></i> Struktur Peta Jabatan</div>
                    <div class="format-badge">Format: Nama Jabatan (Kelas - <span>Eksisting (Bisa diubah)</span> - Kebutuhan)</div>
                </div>

                <div class="tree-container">
                    <div class="root-node">
                        KEPALA BIRO PERENCANAAN
                        <div class="sub-cls">(Kelas 15)</div>
                    </div>
                    <div class="tree-line-h"></div>

                    <div class="branches-grid">
                        <div class="branch-column">
                            <div class="branch-title-card">
                                Kepala Subbagian Tata Usaha
                                <div class="b-cls">(Kelas 9)</div>
                            </div>
                            
                            <div class="job-item-card">
                                <span>Arsiparis Mahir</span>
                                <div class="job-meta">(7 - <span>0</span> - 1)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Pengolah Data dan Informasi</span>
                                <div class="job-meta">(6 - <span>3</span> - 3)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Pengadministrasi Perkantoran</span>
                                <div class="job-meta">(5 - <span>2</span> - 2)</div>
                            </div>
                        </div>

                        <div class="branch-column">
                            <div class="branch-title-card">
                                Kelompok Jabatan Fungsional
                            </div>
                            
                            <div class="job-item-card">
                                <span>Perencana Ahli Madya</span>
                                <div class="job-meta">(12 - <span>3</span> - 7)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Perencana Ahli Muda</span>
                                <div class="job-meta">(10 - <span>17</span> - 21)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Analis Kebijakan Ahli Muda</span>
                                <div class="job-meta">(10 - <span>4</span> - 4)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Analis SDM Aparatur Ahli Madya</span>
                                <div class="job-meta">(12 - <span>10</span> - 15)</div>
                            </div>
                        </div>

                        <div class="branch-column">
                            <div class="branch-title-card">
                                Jabatan Pelaksana
                            </div>
                            
                            <div class="job-item-card">
                                <span>Penelaah Teknis Kebijakan</span>
                                <div class="job-meta">(7 - <span>4</span> - 4)</div>
                            </div>
                            <div class="job-item-card">
                                <span>Penata Layanan Operasional</span>
                                <div class="job-meta">(7 - <span>1</span> - 1)</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
