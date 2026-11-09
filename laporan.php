<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mengambil total jumlah user dari database untuk statistik ringkas
$query_user = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$data_user = mysqli_fetch_assoc($query_user);
$total_users = $data_user['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analisis - SistemKu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; overflow-x: hidden; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { width: 250px; background-color: #212529; color: white; transition: all 0.3s; }
        .sidebar .nav-link { color: #adbcca; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background-color: #343a40; }
        @media (max-width: 768px) { .wrapper { flex-direction: column; } .sidebar { width: 100%; } }
    </style>
</head>
<body>

    <!-- NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-cpu-fill me-2"></i>SistemKu</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="pengaturan.php"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- WRAPPER UTAMA -->
    <div class="wrapper">
        
        <!-- SIDEBAR (Menu Laporan Analisis Sekarang "Active") -->
        <aside class="sidebar d-flex flex-column p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link rounded-0">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="user.php" class="nav-link rounded-0">
                        <i class="bi bi-people me-2"></i> Manajemen User
                    </a>
                </li>
                <li>
                    <a href="laporan.php" class="nav-link active rounded-0">
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

        <!-- KONTEN UTAMA -->
        <main class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Laporan Analisis</h1>
                </div>

                <!-- Baris Info Ringkas -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 bg-primary text-white">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase mb-1 opacity-75 small">Total Akun Terdaftar</h6>
                                    <h2 class="fw-bold mb-0"><?= $total_users; ?></h2>
                                </div>
                                <i class="bi bi-people-fill fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 bg-success text-white">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div>
                                    <h6 class="text-uppercase mb-1 opacity-75 small">Rata-rata Uptime</h6>
                                    <h2 class="fw-bold mb-0">99.9%</h2>
                                </div>
                                <i class="bi bi-activity fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris Grafik Visual -->
                <div class="row g-4">
                    <!-- Grafik Batang -->
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-graph-up me-2"></i>Statistik Aktivitas Bulanan</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartBatang" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Lingkaran -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-pie-chart-fill me-2"></i>Status Perangkat Akses</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="chartLingkaran" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto border-top border-secondary">
        <div class="container">
            <small>&copy; <?= date('Y'); ?> SistemKu Enterprise. All Rights Reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle -->
    <script src="https://jsdelivr.net" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Pustaka Chart.js untuk menggambar Grafik -->
    <script src="https://jsdelivr.net"></script>
    
    <script>
        // 1. Logika Pembuatan Grafik Batang
        const ctxBar = document.getElementById('chartBatang').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data:,
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Logika Pembuatan Grafik Lingkaran (Pie Chart)
        const ctxPie = document.getElementById('chartLingkaran').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Desktop', 'Mobile', 'Tablet'],
                datasets: [{
                    data:,
                    backgroundColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
