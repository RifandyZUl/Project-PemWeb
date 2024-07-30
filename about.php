<?php

// Menyertakan file konfigurasi (menggunakan @ untuk menyembunyikan kesalahan, pertimbangkan untuk menangani kesalahan dengan lebih baik).
@include 'config.php';

// Memulai sesi pengguna atau melanjutkan sesi yang sudah ada.
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
   <title>about</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?> <!-- Menyertakan file header -->

<section class="about"> <!-- Bagian tentang perusahaan -->

   <div class="row"> <!-- Baris untuk menampilkan informasi -->

      <div class="box"> <!-- Kotak pertama informasi -->
         <img src="images/about-img-1.png" alt=""> <!-- Gambar informasi pertama -->
         <h3>why choose us?</h3> <!-- Judul informasi pertama -->
         <p>"Kami menyediakan produk berkualitas tinggi dengan layanan pelanggan yang sangat baik. Setiap produk kami dipilih dengan hati-hati untuk memastikan Anda mendapatkan yang terbaik. Nikmati proses belanja yang mudah dan pengalaman yang menyenangkan bersama kami."</p> <!-- Deskripsi informasi pertama -->
         <a href="contact.php" class="btn">contact us</a> <!-- Tautan untuk menghubungi kami -->
      </div>

      <div class="box"> <!-- Kotak kedua informasi -->
         <img src="images/about-img-2.png" alt=""> <!-- Gambar informasi kedua -->
         <h3>what we provide?</h3> <!-- Judul informasi kedua -->
         <p>"Kami menyediakan produk berkualitas tinggi dengan fokus pada kesegaran dan rasa. Dengan memilih pemasok terbaik dan memeriksa setiap item secara ketat, kami memastikan bahwa Anda mendapatkan yang terbaik. Nikmati belanja mudah dengan jaminan kepuasan dan nilai dari setiap produk yang kami tawarkan."</p> <!-- Deskripsi informasi kedua -->
         <a href="shop.php" class="btn">our shop</a> <!-- Tautan ke halaman toko -->
      </div>

   </div>

</section>

<section class="reviews"> <!-- Bagian ulasan pelanggan -->

   <h1 class="tittle">clients reviews</h1> <!-- Judul ulasan pelanggan -->

   <div class="box-container"> <!-- Kontainer untuk ulasan -->

      <div class="box"> <!-- Kotak ulasan pertama -->
        <div class="img-container"> <!-- Kontainer gambar -->
        <img src="images/pic-1.png" alt=""> <!-- Gambar pelanggan pertama -->
        </div>
         <p>"Saya sangat puas dengan kualitas produk dan layanan yang diberikan. Sayuran dan buah-buahan selalu segar dan daging serta ikan selalu dalam kondisi terbaik. Belanja di sini adalah pengalaman yang menyenangkan dan kami akan terus kembali!"</p> <!-- Ulasan pelanggan pertama -->
         <div class="stars"> <!-- Kontainer bintang rating -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
         </div>
         <h3>Anselma Putri</h3> <!-- Nama pelanggan pertama -->
      </div>

      <div class="box"> <!-- Kotak ulasan kedua -->
        <div class="img-container"> <!-- Kontainer gambar -->
        <img src="images/pic-2.png" alt=""> <!-- Gambar pelanggan kedua -->
        </div>
         <p>"Produk yang ditawarkan sangat berkualitas dan selalu memenuhi harapan kami. Layanan pelanggan juga sangat responsif dan membantu. Marketplace ini benar-benar menjadi andalan kami untuk kebutuhan makanan sehari-hari."</p> <!-- Ulasan pelanggan kedua -->
         <div class="stars"> <!-- Kontainer bintang rating -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star-half-alt"></i> <!-- Bintang setengah -->
         </div>
         <h3>Pa Ma'ruf</h3> <!-- Nama pelanggan kedua -->
      </div>

      <div class="box"> <!-- Kotak ulasan ketiga -->
        <div class="img-container"> <!-- Kontainer gambar -->
        <img src="images/pic-3.png" alt=""> <!-- Gambar pelanggan ketiga -->
        </div>
      
         <p>"Pengalaman belanja kami sangat memuaskan. Semua produk yang kami terima selalu segar dan sesuai dengan deskripsi. Kami juga sangat menghargai layanan pelanggan yang ramah dan efisien."</p> <!-- Ulasan pelanggan ketiga -->
         <div class="stars"> <!-- Kontainer bintang rating -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star-half-alt"></i> <!-- Bintang setengah -->
         </div>
         <h3>john deo</h3> <!-- Nama pelanggan ketiga -->
      </div>

      <div class="box"> <!-- Kotak ulasan keempat -->
        <div class="img-container"> <!-- Kontainer gambar -->
         <img src="images/pic-4.png" alt=""> <!-- Gambar pelanggan keempat -->
        </div>
         <p>"Kualitas produk yang disediakan sangat baik, dan proses pemesanan serta pengirimannya sangat cepat. Marketplace ini adalah pilihan utama kami untuk membeli bahan makanan berkualitas."</p> <!-- Ulasan pelanggan keempat -->
         <div class="stars"> <!-- Kontainer bintang rating -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star-half-alt"></i> <!-- Bintang setengah -->
         </div>
         <h3>Megawati</h3> <!-- Nama pelanggan keempat -->
      </div>

      <div class="box"> <!-- Kotak ulasan kelima -->
        <div class="img-container"> <!-- Kontainer gambar -->
        <img src="images/pic-5.png" alt=""> <!-- Gambar pelanggan kelima -->
        </div>
         <p>"Kami sangat merekomendasikan marketplace ini. Produk-produk segar dan layanan pelanggan yang luar biasa menjadikan pengalaman belanja kami sangat memuaskan. Terima kasih atas kualitas dan perhatian yang diberikan!"</p> <!-- Ulasan pelanggan kelima -->
         <div class="stars"> <!-- Kontainer bintang rating -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
            <i class="fas fa-star"></i> <!-- Bintang penuh -->
         </div>
         <h3>Syifa Izul</h3> <!-- Nama pelanggan kelima -->
      </div>

   </div>

</section>

<?php include 'footer.php'; ?> <!-- Menyertakan file footer -->

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
