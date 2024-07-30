<?php

// Menyertakan file konfigurasi, menggunakan @ untuk menyembunyikan kesalahan. Pertimbangkan untuk menangani kesalahan dengan lebih baik.
@include 'config.php';

// Memulai atau melanjutkan sesi pengguna.
session_start();

// Mengambil ID pengguna dari sesi.
$user_id = $_SESSION['user_id'];

// Mengecek apakah user_id sudah diset dalam sesi.
if(!isset($user_id)){
   // Jika user_id tidak diset, arahkan pengguna ke halaman login.
   header('location:login.php');
   exit(); // Menghentikan eksekusi script setelah pengalihan.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?> <!-- Menyertakan file header -->

<section class="placed-orders"> <!-- Bagian untuk menampilkan pesanan yang ditempatkan -->

   <h1 class="tittle">placed orders</h1> <!-- Judul untuk daftar pesanan yang ditempatkan -->

   <div class="box-container"> <!-- Container untuk menampilkan setiap pesanan -->

   <?php
      // Mempersiapkan query untuk memilih pesanan berdasarkan ID pengguna.
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      // Menjalankan query dengan parameter ID pengguna.
      $select_orders->execute([$user_id]);
      
      // Mengecek jika ada pesanan untuk pengguna ini.
      if($select_orders->rowCount() > 0){
         // Mengambil dan menampilkan setiap pesanan.
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box"> <!-- Container untuk menampilkan detail pesanan -->
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p> <!-- Tanggal pesanan -->
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p> <!-- Nama pengguna -->
      <p> Phone Number : <span><?= $fetch_orders['number']; ?></span> </p> <!-- Nomor telepon pengguna -->
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p> <!-- Email pengguna -->
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p> <!-- Alamat pengiriman -->
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p> <!-- Metode pembayaran -->
      <p> Your Orders : <span><?= $fetch_orders['total_products']; ?></span> </p> <!-- Jumlah produk yang dipesan -->
      <p> Total Price : <span>Rp<?= $fetch_orders['total_price']; ?></span> </p> <!-- Harga total pesanan -->
      <p> Payment Status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p> <!-- Status pembayaran dengan warna yang menandakan status -->
   </div>
   <?php
      }
   }else{
      // Jika tidak ada pesanan, tampilkan pesan bahwa tidak ada pesanan yang ditempatkan.
      echo '<p class="empty">no orders placed yet!</p>';
   }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?> <!-- Menyertakan file footer -->

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
