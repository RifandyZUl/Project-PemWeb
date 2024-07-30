<?php
// Konfigurasi koneksi database
$host = 'localhost'; // Host database
$db_name = 'shop_db'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    // Membuat koneksi PDO
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mengaktifkan mode error
} catch (PDOException $e) {
    // Menangani kesalahan koneksi
    echo "Koneksi gagal: " . $e->getMessage();
    die(); // Menghentikan eksekusi jika koneksi gagal
}
?>
