<?php
$host = "localhost";
$user = "";
$pass = "";
$db   = "db_sistem";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
