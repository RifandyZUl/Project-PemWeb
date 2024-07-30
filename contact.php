<?php

// Sertakan file konfigurasi, dengan penanganan kesalahan lebih baik daripada menggunakan '@'
include 'config.php';

// Mulai sesi pengguna atau lanjutkan sesi yang ada
session_start();

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Periksa apakah user_id sudah diset dalam sesi
if (!isset($user_id)) {
    // Jika user_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Hentikan eksekusi skrip setelah pengalihan
}

// Tangani pengiriman formulir jika tombol "send" ditekan
if (isset($_POST['send'])) {
    // Ambil data dari formulir dan sanitasi input untuk menghindari karakter yang tidak diinginkan
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    // Mempersiapkan query untuk memeriksa apakah pesan dengan detail yang sama sudah ada di database
    $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    // Jalankan query dengan parameter yang diberikan
    $select_message->execute([$name, $email, $number, $msg]);

    // Periksa apakah ada baris yang ditemukan dengan query sebelumnya
    if ($select_message->rowCount() > 0) {
        // Jika pesan yang sama sudah ada, simpan pesan kesalahan
        $message[] = 'Pesan sudah dikirim!';
    } else {
        // Jika pesan belum ada, simpan pesan baru ke database
        $insert_message = $conn->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        // Jalankan query insert dengan parameter yang diberikan
        $insert_message->execute([$user_id, $name, $email, $number, $msg]);

        // Simpan pesan sukses
        $message[] = 'Pesan berhasil dikirim!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kontak</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Menyertakan file header -->
<?php include 'header.php'; ?>

<section class="contact"> <!-- Bagian untuk formulir kontak -->

   <h1 class="tittle">Hubungi Kami</h1> <!-- Judul formulir kontak -->

   <!-- Formulir kontak yang dikirimkan ke skrip yang sama -->
   <form action="" method="POST"> 
      <!-- Input untuk nama pengguna -->
      <input type="text" name="name" class="box" required placeholder="Masukkan nama Anda"> 
      <!-- Input untuk email pengguna -->
      <input type="email" name="email" class="box" required placeholder="Masukkan email Anda"> 
      <!-- Input untuk nomor telepon pengguna -->
      <input type="number" name="number" min="0" class="box" required placeholder="Masukkan nomor telepon Anda"> 
      <!-- Area teks untuk pesan pengguna -->
      <textarea name="msg" class="box" required placeholder="Masukkan pesan Anda" cols="30" rows="10"></textarea> 
      <!-- Tombol untuk mengirimkan formulir -->
      <input type="submit" value="Kirim Pesan" class="btn" name="send"> 
   </form>

</section>

<!-- Menyertakan file footer -->
<?php include 'footer.php'; ?>

<!-- Link ke file JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
