<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Cek apakah session sudah terisi atau belum
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";
$username_sekarang = $_SESSION['username'];

// 1. PROSES UPDATE USERNAME
if (isset($_POST['update_profil'])) {
    $username_baru = mysqli_real_escape_string($conn, trim($_POST['username']));

    if (empty($username_baru)) {
        $error = "Username tidak boleh kosong!";
    } else {
        // Cek apakah username baru sudah dipakai orang lain
        $cek_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username_baru' AND username != '$username_sekarang'");
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username sudah digunakan oleh akun lain!";
        } else {
            // Update data ke database
            $query_update = "UPDATE users SET username = '$username_baru' WHERE username = '$username_sekarang'";
            if (mysqli_query($conn, $query_update)) {
                // Perbarui session dengan username yang baru
                $_SESSION['username'] = $username_baru;
                $success = "Username berhasil diperbarui!";
                header("refresh:1.5;url=pengaturan.php");
            } else {
                $error = "Gagal memperbarui profil.";
            }
        }
    }
}

// 2. PROSES UPDATE PASSWORD
if (isset($_POST['update_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        $error = "Semua kolom password wajib diisi!";
    } elseif ($password_baru !== $konfirmasi_password) {
        $error = "Konfirmasi password baru tidak cocok!";
    } else {
        // Ambil data password lama dari database untuk dicocokkan
        $query_cari = mysqli_query($conn, "SELECT password FROM users WHERE username = '{$_SESSION['username']}'");
        $data_user = mysqli_fetch_assoc($query_cari);

        // Verifikasi apakah password lama yang diinput sudah benar
        if (password_verify($password_lama, $data_user['password'])) {
            // Hash password baru sebelum disimpan
            $password_aman = password_hash($password_baru, PASSWORD_DEFAULT);
            $query_pass = "UPDATE users SET password = '$password_aman' WHERE username = '{$_SESSION['username']}'";
            
            if (mysqli_query($conn, $query_pass)) {
                $success = "Password berhasil diubah!";
                header("refresh:1.5;url=pengaturan.php");
            } else {
                $error = "Gagal memperbarui password.";
            }
        } else {
            $error = "Password lama Anda salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setelan Sistem - SistemKu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet">
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

    <!-- 1. NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-cpu-fill me-2"></i>SistemKu</a>
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
        
        <!-- 2. SIDEBAR (Menu Setelan Sistem Sekarang "Active") -->
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
                    <a href="laporan.php" class="nav-link rounded-0">
                        <i class="bi bi-bar-chart me-2"></i> Laporan Analisis
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" class="nav-link active rounded-0">
                        <i class="bi bi-gear me-2"></i> Setelan Sistem
                    </a>
                </li>
            </ul>
        </aside>

        <!-- 3. KONTEN UTAMA -->
        <main class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid" style="max-width: 800px;">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Setelan Akun & Sistem</h1>
                </div>

                <!-- Notifikasi Real-time -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Form 1: Ubah Informasi Profil -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-person-gear me-2"></i>Ubah Profil</h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="pengaturan.php" method="POST">
                                    <div class="mb-4">
                                        <label for="username" class="form-label fw-semibold">Username Anda Sekarang</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($_SESSION['username']); ?>" required autocomplete="off">
                                    </div>
                                    <button type="submit" name="update_profil" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Perbarui Profil</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Form 2: Ganti Password -->
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-shield-lock me-2"></i>Ganti Password keamanan</h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="pengaturan.php" method="POST">
                                    <div class="mb-3">
                                        <label for="password_lama" class="form-label fw-semibold">Password Lama</label>
                                        <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_baru" class="form-label fw-semibold">Password Baru</label>
