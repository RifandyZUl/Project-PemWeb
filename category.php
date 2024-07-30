<?php

// Menyertakan file konfigurasi. Menggunakan @ untuk menyembunyikan kesalahan, pertimbangkan untuk menangani kesalahan dengan lebih baik.
@include 'config.php'; 

// Memulai sesi atau melanjutkan sesi yang sudah ada
session_start(); 

// Mengambil ID pengguna dari sesi
$user_id = $_SESSION['user_id']; 

// Mengecek apakah user_id sudah diset dalam sesi
if(!isset($user_id)){
   // Jika user_id tidak diset, arahkan pengguna ke halaman login
   header('location:login.php');
   exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Menangani penambahan produk ke wishlist
if(isset($_POST['add_to_wishlist'])){

   // Mengambil dan membersihkan data dari form
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

   // Mengecek apakah produk sudah ada di wishlist
   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   // Mengecek apakah produk sudah ada di cart
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   // Menangani kondisi produk yang sudah ada
   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      // Menambahkan produk ke wishlist jika belum ada
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }
}

// Menangani penambahan produk ke cart
if(isset($_POST['add_to_cart'])){

   // Mengambil dan membersihkan data dari form
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
   $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

   // Mengecek apakah produk sudah ada di cart
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      // Mengecek apakah produk ada di wishlist dan menghapusnya jika ada
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      // Menambahkan produk ke cart
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">

   <h1 class="tittle">products categories</h1> <!-- Judul bagian produk -->

   <div class="box-container">

   <?php
      // Mengambil nama kategori dari URL
      $category_name = $_GET['category'];
      
      // Mengambil produk berdasarkan kategori
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
      $select_products->execute([$category_name]);

      // Menampilkan produk jika ada
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">Rp<span><?= $fetch_products['price']; ?></span>/-</div> <!-- Menampilkan harga produk -->
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> <!-- Tautan ke halaman detail produk -->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""> <!-- Gambar produk -->
      <div class="name"><?= $fetch_products['name']; ?></div> <!-- Nama produk -->
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>"> <!-- ID produk -->
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>"> <!-- Nama produk -->
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>"> <!-- Harga produk -->
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>"> <!-- Gambar produk -->
      <input type="number" min="1" value="1" name="p_qty" class="qty"> <!-- Input kuantitas produk -->
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist"> <!-- Tombol untuk menambah ke wishlist -->
      <input type="submit" value="add to cart" class="btn" name="add_to_cart"> <!-- Tombol untuk menambah ke cart -->
   </form>
   <?php
         }
      }else{
         // Menampilkan pesan jika tidak ada produk
         echo '<p class="empty">no products available!</p>';
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
