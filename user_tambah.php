<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Cek apakah username sudah terdaftar di database
        $cek_user = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
        
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username sudah digunakan oleh akun lain!";
        } else {
            // Enkripsi password menggunakan Bcrypt (Algoritma standar PHP)
            $password_aman = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data ke database
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_aman')";
            if (mysqli_query($conn, $query)) {
                $success = "User baru berhasil ditambahkan!";
                // Opsional: Redirect otomatis setelah 2 detik kembali ke halaman utama manajemen
                header("refresh:2;url=user.php");
            } else {
                $error = "Gagal menyimpan data ke sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - SistemKu</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link rel="stylesheet" href="https://jsdelivr.net">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; overflow-x: hidden; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { width: 250px; background-color: #212529; color: white; }
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
            <div class="ms-auto">
                <span class="text-white"><i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </div>
    </nav>

    <!-- WRAPPER UTAMA -->
    <div class="wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar d-flex flex-column p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li><a href="dashboard.php" class="nav-link rounded-0"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                <li><a href="user.php" class="nav-link active rounded-0"><i class="bi bi-people me-2"></i> Manajemen User</a></li>
                <li><a href="laporan.php" class="nav-link rounded-0"><i class="bi bi-bar-chart me-2"></i> Laporan Analisis</a></li>
                <li><a href="pengaturan.php" class="nav-link rounded-0"><i class="bi bi-gear me-2"></i> Setelan Sistem</a></li>
            </ul>
        </aside>

        <!-- KONTEN FORM -->
        <main class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid" style="max-width: 600px; margin: 0 auto;">
                <div class="pt-3 pb-2 mb-3 border-bottom d-flex align-items-center justify-content-between">
                    <h1 class="h3">Tambah User Baru</h1>
                    <a href="user.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                </div>

                <!-- Notifikasi Status -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success; ?>. <em>Mengalihkan halaman...</em></div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="user_tambah.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username Baru</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username..." required autocomplete="off">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password Akun</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password unik..." required>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Daftarkan User</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto border-top border-secondary">
        <small>&copy; <?= date('Y'); ?> SistemKu Enterprise. All Rights Reserved.</small>
    </footer>

</body>
</html>
