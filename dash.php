<?php
session_start();

// Proteksi halaman: Cek apakah session sudah terisi atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sistem</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons (Untuk Icon Sidebar) -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* Mengatur layout utama agar footer tetap di bawah */
        .wrapper {
            display: flex;
            flex: 1;
        }
        /* Styling Sidebar */
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #adbcca;
            padding: 12px 20px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: #343a40;
        }
        /* Responsif Sidebar untuk Layar HP */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

        <!-- 1. NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
        <div class="container-fluid">
            <!-- Link Logo kembali ke Dashboard -->
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-cpu-fill me-2"></i>SistemKu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Link Pengaturan di Navbar -->
                            <li><a class="dropdown-item" href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Link Keluar (Sudah terhubung ke logout.php) -->
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- WRAPPER UTAMA (SIDEBAR + KONTEN) -->
    <div class="wrapper">
        
        <!-- 2. SIDEBAR -->
        <aside class="sidebar d-flex flex-column p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <!-- Aktif karena ini halaman dashboard -->
                    <a href="dashboard.php" class="nav-link active rounded-0">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="user.php" class="nav-link rounded-0">
                        <i class="bi bi-people me-2"></i> Manajemen User
                    </a>
                </li>
                <li>
                    <a href="laporan.php" class="nav-link rounded-0">
                        <i class="bi bi-bar-chart me-2"></i> Laporan Analisis
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" class="nav-link rounded-0">
                        <i class="bi bi-gear me-2"></i> Setelan Sistem
                    </a>
                </li>
            </ul>
        </aside>

        <!-- 3. KONTEN UTAMA -->
        <main class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <span class="badge bg-primary p-2">Status: Online</span>
                </div>

                <!-- Kartu Informasi (Stats Cards) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-primary border-4">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-1">Total Pengguna</h6>
                                <h2 class="card-title fw-bold mb-0">1,250</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-success border-4">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-1">Kunjungan Hari Ini</h6>
                                <h2 class="card-title fw-bold mb-0">432</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-warning border-4">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted mb-1">Pemberitahuan</h6>
                                <h2 class="card-title fw-bold mb-0">3 Baru</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Isi Utama -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Selamat Datang Kembali, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>! 👋</h5>
                        <p class="text-secondary mb-0">Ini adalah halaman dashboard yang telah diperbarui menggunakan komponen Navbar atas, Sidebar samping untuk navigasi menu, serta Footer statis di bagian bawah halaman.</p>
                    </div>
                </div>
            </div>
        </main>

    </div>

    <!-- 4. FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto border-top border-secondary">
        <div class="container">
            <small>&copy; <?= date('Y'); ?> SistemKu Enterprise. All Rights Reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle (Wajib untuk Dropdown & Navigasi Mobile) -->
    <script src="https://jsdelivr.net" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
