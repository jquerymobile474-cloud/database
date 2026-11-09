<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Cek apakah session sudah terisi atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari database
$query = "SELECT id, username FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - SistemKu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
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
        
        <!-- 2. SIDEBAR (Menu Manajemen User Sekarang "Active") -->
        <aside class="sidebar d-flex flex-column p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link rounded-0">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="user.php" class="nav-link active rounded-0">
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
                    <h1 class="h2">Manajemen User</h1>
                    <!-- Tombol Tambah Data -->
                    <a href="user_tambah.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah User Baru
                    </a>
                </div>

                <!-- Tabel Data -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary"><i class="bi bi-table me-2"></i>Daftar Akun Pengguna</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 80px;" class="text-center">No</th>
                                        <th>Username</th>
                                        <th style="width: 200px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if (mysqli_num_rows($result) > 0):
                                        while ($row = mysqli_fetch_assoc($result)): 
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="fw-semibold text-secondary"><?= htmlspecialchars($row['username']); ?></td>
                                            <td class="text-center">
                                                <!-- Tombol Aksi -->
                                                <a href="user_edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm me-1" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="user_hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        endwhile; 
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">Belum ada data user.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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

    <!-- Bootstrap 5 JS -->
    <script src="https://jsdelivr.net"></script>
</body>
</html>
