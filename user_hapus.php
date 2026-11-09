<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Cek apakah user berhak mengakses skrip ini
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mengecek parameter 'id' yang dikirimkan melalui URL alamat browser
if (isset($_GET['id'])) {
    $id_user = intval($_GET['id']); // Menggunakan intval untuk mencegah celah SQL Injection

    // Jalankan perintah hapus data berdasarkan id
    $query = "DELETE FROM users WHERE id = $id_user";
    $eksekusi = mysqli_query($conn, $query);

    if ($eksekusi) {
        // Jika sukses terhapus, kembalikan ke manajemen user dengan pemberitahuan sukses via URL (opsional)
        header("Location: user.php?status=terhapus");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data dari database.'); window.location='user.php';</script>";
        exit();
    }
} else {
    // Jika file diakses langsung tanpa membawa ID parameter, langsung lempar balik
    header("Location: user.php");
    exit();
}
?>
