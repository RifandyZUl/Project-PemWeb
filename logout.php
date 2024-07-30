<?php

// Menyertakan file konfigurasi untuk koneksi database, menggunakan @ untuk menekan pesan kesalahan
@include 'config.php';

// Memulai sesi
session_start();

// Menghapus semua variabel sesi
session_unset();

// Menghancurkan sesi yang sedang berjalan
session_destroy();

// Mengarahkan pengguna ke halaman login
header('location:login.php');

exit();

?>
