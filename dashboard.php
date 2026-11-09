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
    <title>Dashboard</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="container mt-5">
    
    <div class="p-5 mb-4 bg-light rounded-3 border">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>! 🎉</h1>
            <p class="col-md-8 fs-4">Anda telah berhasil masuk ke dalam sistem yang terproteksi menggunakan Session PHP dan basis data MySQL.</p>
            <hr class="my-4">
            <a href="logout.php" class="btn btn-danger btn-lg">Keluar dari Sistem</a>
        </div>
    </div>

</body>
</html>
