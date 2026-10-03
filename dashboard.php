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
    <title>Dashboard - SIMPEG Kementerian ESDM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;
            --bg-body: #E0F2FE;
            --text-main: #334155;
            --sidebar-width: 330px; /* Lebar sidebar disamakan agar muat teks panjang */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); display: flex; min-height: 100vh; color: var(--text-main); }

        /* Sidebar Putih Bersih */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; padding: 25px 20px; display: flex; flex-direction: column; border-right: 1px solid #E2E8F0; position: fixed; height: 100vh; overflow-y: auto; box-shadow: 4px 0 15px rgba(0,0,0,0.04); }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #E2E8F0; color: var(--primary-esdm); }
        .sidebar-brand i { color: var(--accent-gold); font-size: 22px; }
        
        .sidebar ul { list-style: none; padding: 0; flex: 1; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a { color: #64748B; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: 0.2s; white-space: nowrap; }
        .sidebar ul li a:hover { background-color: #F1F5F9; color: var(--primary-esdm); }
        
        /* Dropdown Styling */
        .submenu { list-style: none; padding-left: 20px; margin-top: 6px; display: none; border-left: 2px solid #E2E8F0; margin-left: 15px; }
        .submenu li a { font-size: 13px; padding: 8px 10px; color: #64748B; font-weight: 600; white-space: normal; }
        .submenu li a:hover { color: var(--primary-esdm); }

        /* Main Content Area (Wajib menggunakan main-content agar isi tampil) */
        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; }
        
        /* Top Header */
        .top-header { background: var(--primary-esdm); padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .top-header span { font-size: 16px; font-weight: 700; color: #FFFFFF; letter-spacing: 0.5px; }

        /* Content Body */
        .content-body { padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
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
                <a href="dashboard.php" style="background: #F1F5F9; color: var(--primary-esdm); font-weight: 700;">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            
            <!-- Menu Dropdown Kelola Peta Jabatan dengan Pemisahan Tombol Panah -->
            <li class="has-submenu" style="position: relative; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; border-radius: 8px; overflow: hidden;">
                    <a href="kelola_peta_jabatan.php" style="flex: 1; color: #64748B; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan
                    </a>
                    <span id="btn-toggle-dropdown" style="padding: 12px 15px; cursor: pointer; color: #64748B; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.03);">
                        <i class="fa-solid fa-chevron-down arrow-icon" style="font-size: 11px;"></i>
                    </span>
                </div>

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
        <div class="top-header">
            <span>Dashboard Administrator</span>
        </div>

        <div class="content-body">
            <div class="card">
                <h2 style="color: var(--primary-esdm); margin-bottom: 10px;">Selamat Datang di Dashboard SIMPEG</h2>
                <p>Sistem Informasi Manajemen Kepegawaian Kementerian Energi dan Sumber Daya Mineral.</p>
            </div>
        </div>
    </div>

    <!-- Script Dropdown Khusus Ikon Panah -->
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
