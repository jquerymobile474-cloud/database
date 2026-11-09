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

// Cek apakah parameter 'id' ada di URL
if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit();
}

$id_user = intval($_GET['id']);

// Ambil data user lama berdasarkan ID untuk ditampilkan di form input
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = $id_user");
if (mysqli_num_rows($query_user) === 0) {
    header("Location: user.php");
    exit();
}
$data = mysqli_fetch_assoc($query_user);

// Proses Update Data saat tombol simpan ditekan
if (isset($_POST['update'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password_baru = $_POST['password'];

    if (empty($username)) {
        $error = "Username tidak boleh kosong!";
    } else {
        // Cek apakah username diubah dan apakah username baru sudah dipakai orang lain
        $cek_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' AND id != $id_user");
        if (mysqli_num_rows($cek_username) > 0) {
            $error = "Username sudah digunakan oleh akun lain!";
        } else {
            
            // Logika Fleksibel Password: Jika diisi, di-hash ulang. Jika kosong, pakai password lama.
            if (!empty($password_baru)) {
                $password_aman = password_hash($password_baru, PASSWORD_DEFAULT);
                $query_update = "UPDATE users SET username = '$username', password = '$password_aman' WHERE id = $id_user";
            } else {
                $query_update = "UPDATE users SET username = '$username' WHERE id = $id_user";
            }

            if (mysqli_query($conn, $query_update)) {
                $success = "Data user berhasil diperbarui!";
                header("refresh:2;url=user.php");
            } else {
                $error = "Gagal memperbarui data ke database.";
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
    <title>Edit User - SistemKu</title>
    <!-- Bootstrap 5 CSS via CDN alternatif stabil -->
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

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-cpu-fill me-2"></i>SistemKu</a>
        </div>
    </nav>

    <!-- WRAPPER -->
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

        <!-- KONTEN UTAMA -->
        <main class="flex-grow-1 p-4 bg-light">
            <div class="container-fluid" style="max-width: 600px; margin: 0 auto;">
                <div class="pt-3 pb-2 mb-3 border-bottom d-flex align-items-center justify-content-between">
                    <h1 class="h3">Edit Data User</h1>
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
                        <form action="user_edit.php?id=<?= $id_user; ?>" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username Akun</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($data['username']); ?>" required autocomplete="off">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password lama">
                                <div class="form-text text-muted">Keamanan: Password lama tetap aktif jika kolom ini dibiarkan kosong.</div>
                            </div>
                            <button type="submit" name="update" class="btn btn-warning w-100 fw-bold"><i class="bi bi-check-circle me-1"></i> Simpan Perubahan</button>
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

    <script src="https://jsdelivr.net"></script>
</body>
</html>
