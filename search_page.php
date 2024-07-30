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

// Mengecek jika tombol "add_to_wishlist" diklik.
if(isset($_POST['add_to_wishlist'])){

   // Mengambil dan menyaring data dari formulir.
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   // Mengecek apakah produk sudah ada di wishlist.
   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   // Mengecek apakah produk sudah ada di cart.
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   // Menampilkan pesan jika produk sudah ada di wishlist atau cart.
   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      // Menambahkan produk ke wishlist jika belum ada.
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

// Mengecek jika tombol "add_to_cart" diklik.
if(isset($_POST['add_to_cart'])){

   // Mengambil dan menyaring data dari formulir.
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   // Mengecek apakah produk sudah ada di cart.
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   // Menampilkan pesan jika produk sudah ada di cart.
   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      // Mengecek apakah produk sudah ada di wishlist.
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      // Menghapus produk dari wishlist jika sudah ada di cart.
      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      // Menambahkan produk ke cart.
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
   <title>search page</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?> <!-- Menyertakan file header -->

<section class="search-form"> <!-- Bagian formulir pencarian produk -->

   <form action="" method="POST">
      <input type="text" class="box" name="search_box" placeholder="search products..."> <!-- Input untuk mencari produk -->
      <input type="submit" name="search_btn" value="search" class="btn"> <!-- Tombol untuk mengirim pencarian -->
   </form>

</section>

<?php

// Formulir pencarian produk sudah ditangani di atas, tidak ada tambahan PHP di sini.

?>

<section class="products" style="padding-top: 0; min-height:100vh;"> <!-- Bagian untuk menampilkan produk berdasarkan pencarian -->

   <div class="box-container"> <!-- Container untuk menampilkan setiap produk -->

   <?php
      // Mengecek jika tombol pencarian diklik.
      if(isset($_POST['search_btn'])){
         // Mengambil dan menyaring data pencarian.
         $search_box = $_POST['search_box'];
         $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
         // Mempersiapkan query untuk memilih produk yang sesuai dengan pencarian.
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%' OR category LIKE '%{$search_box}%'");
         $select_products->execute();
         
         // Mengecek jika ada produk yang ditemukan.
         if($select_products->rowCount() > 0){
            // Mengambil dan menampilkan setiap produk yang ditemukan.
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div> <!-- Harga produk -->
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> <!-- Tautan untuk melihat detail produk -->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""> <!-- Gambar produk -->
      <div class="name"><?= $fetch_products['name']; ?></div> <!-- Nama produk -->
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>"> <!-- ID produk (tidak terlihat) -->
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>"> <!-- Nama produk (tidak terlihat) -->
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>"> <!-- Harga produk (tidak terlihat) -->
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>"> <!-- Gambar produk (tidak terlihat) -->
      <input type="number" min="1" value="1" name="p_qty" class="qty"> <!-- Input jumlah produk -->
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist"> <!-- Tombol untuk menambahkan ke wishlist -->
      <input type="submit" value="add to cart" class="btn" name="add_to_cart"> <!-- Tombol untuk menambahkan ke cart -->
   </form>
   <?php
            }
         }else{
            // Jika tidak ada produk ditemukan, tampilkan pesan.
            echo '<p class="empty">no result found!</p>';
         }
      }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?> <!-- Menyertakan file footer -->

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
