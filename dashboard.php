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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-esdm: #0A192F;
            --secondary-esdm: #172A45;
            --accent-gold: #C5A059;
            --bg-body: #E0F2FE;
            --text-main: #334155;
            --sidebar-width: 260px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); display: flex; min-height: 100vh; color: var(--text-main); }
        
        /* Sidebar Styling */
        .sidebar { width: var(--sidebar-width); background: #FFFFFF; padding: 25px 20px; display: flex; flex-direction: column; border-right: 1px solid #E2E8F0; }
        .sidebar-brand { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 700; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #E2E8F0; color: var(--primary-esdm); }
        .sidebar-brand i { color: var(--accent-gold); font-size: 22px; }
        .sidebar ul { list-style: none; padding: 0; flex: 1; }
        .sidebar ul li { margin-bottom: 8px; }
        .sidebar ul li a { color: #64748B; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-radius: 8px; font-size: 14px; font-weight: 500; transition: 0.2s; }
        .sidebar ul li a:hover, .sidebar ul li a.active { background-color: #F1F5F9; color: var(--primary-esdm); }
        
        /* Dropdown Styling */
        .submenu { list-style: none; padding-left: 25px; margin-top: 5px; display: none; }
        .submenu.show { display: block; }
        .submenu li a { font-size: 13px; padding: 8px 10px; color: #64748B; font-weight: 600; }
        .submenu li a:hover { color: var(--primary-esdm); }

        /* Content Area */
        .content { flex: 1; padding: 30px; }
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
                <a href="dashboard.php" class="active">
                    <i class="fa-solid fa-chart-pie" style="color: var(--accent-gold);"></i> Dashboard
                </a>
            </li>
            
            <!-- Menu Dropdown Kelola Peta Jabatan -->
            <li class="has-submenu">
                <a href="#" class="dropdown-toggle" id="menu-peta">
                    <i class="fa-solid fa-sitemap"></i> Kelola Peta Jabatan 
                    <i class="fa-solid fa-chevron-down arrow-icon" style="margin-left: auto; font-size: 11px;"></i>
                </a>
                <ul class="submenu" id="submenu-peta">
                    <li><a href="sekretariat_jenderal.php">Sekretariat Jenderal</a></li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa-solid fa-pen-to-square"></i> Usulan Tambah/Edit
                </a>
            </li>

            <li style="margin-top: 20px;">
                <a href="logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="card">
            <h2 style="color: var(--primary-esdm); margin-bottom: 10px;">Selamat Datang di Dashboard SIMPEG</h2>
            <p>Sistem Informasi Manajemen Kepegawaian Kementerian Energi dan Sumber Daya Mineral.</p>
        </div>
    </div>

    <!-- Script Dropdown Naik Turun -->
    <script>
        const dropdownToggle = document.getElementById('menu-peta');
        const submenu = document.getElementById('submenu-peta');
        const arrowIcon = dropdownToggle.querySelector('.arrow-icon');

        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            submenu.classList.toggle('show');
            
            if (submenu.classList.contains('show')) {
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
