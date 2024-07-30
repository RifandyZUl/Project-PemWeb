<?php

// Mengimpor file konfigurasi untuk pengaturan database dan variabel lainnya
@include 'config.php';

// Memulai sesi untuk melacak pengguna yang sedang login
session_start();

// Mendapatkan ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Mengecek apakah ID pengguna tersedia dalam sesi; jika tidak, arahkan pengguna ke halaman login
if(!isset($user_id)){
   header('location:login.php');
   exit(); // Hentikan eksekusi skrip setelah pengalihan
};

// Mengecek apakah form untuk menambah produk ke wishlist telah disubmit
if(isset($_POST['add_to_wishlist'])){

   // Mengambil data produk dari form dan membersihkan input untuk keamanan
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   // Mengecek apakah produk sudah ada di wishlist pengguna
   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   // Mengecek apakah produk sudah ada di cart pengguna
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   // Jika produk sudah ada di wishlist, beri pesan
   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   // Jika produk sudah ada di cart, beri pesan
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   // Jika produk belum ada di wishlist atau cart, tambahkan produk ke wishlist
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

// Mengecek apakah form untuk menambah produk ke cart telah disubmit
if(isset($_POST['add_to_cart'])){

   // Mengambil data produk dari form dan membersihkan input untuk keamanan
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

   // Mengecek apakah produk sudah ada di cart pengguna
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   // Jika produk sudah ada di cart, beri pesan
   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      // Mengecek apakah produk sudah ada di wishlist pengguna
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      // Jika produk ada di wishlist, hapus dari wishlist
      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      // Tambahkan produk ke cart
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
   <title>shop</title>

   <!-- Link CDN untuk Font Awesome (ikon) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?> <!-- Menyertakan header.php untuk bagian header halaman -->

<section class="p-category"> <!-- Mulai bagian kategori produk -->

   <a href="category.php?category=fruits">fruits</a> <!-- Link ke kategori buah -->
   <a href="category.php?category=vegetables">vegetables</a> <!-- Link ke kategori sayur -->
   <a href="category.php?category=fish">fish</a> <!-- Link ke kategori ikan -->
   <a href="category.php?category=meat">meat</a> <!-- Link ke kategori daging -->

</section>

<section class="products"> <!-- Mulai bagian produk -->

   <h1 class="tittle">latest products</h1> <!-- Judul untuk bagian produk terbaru -->

   <div class="box-container"> <!-- Kontainer untuk box produk -->

   <?php
      // Mengambil semua produk dari database
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      // Mengecek jika ada produk yang diambil
      if($select_products->rowCount() > 0){
         // Menampilkan setiap produk dalam box
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST"> <!-- Form untuk setiap produk -->
      <div class="price">Rp<span><?= $fetch_products['price']; ?></span>/-</div> <!-- Menampilkan harga produk -->
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> <!-- Link untuk melihat detail produk -->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""> <!-- Menampilkan gambar produk -->
      <div class="name"><?= $fetch_products['name']; ?></div> <!-- Menampilkan nama produk -->
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>"> <!-- Input tersembunyi untuk ID produk -->
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>"> <!-- Input tersembunyi untuk nama produk -->
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>"> <!-- Input tersembunyi untuk harga produk -->
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>"> <!-- Input tersembunyi untuk gambar produk -->
      <input type="number" min="1" value="1" name="p_qty" class="qty"> <!-- Input untuk jumlah produk yang ingin dibeli -->
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist"> <!-- Tombol untuk menambah produk ke wishlist -->
      <input type="submit" value="add to cart" class="btn" name="add_to_cart"> <!-- Tombol untuk menambah produk ke cart -->
   </form>
   <?php
      }
   // Jika tidak ada produk, tampilkan pesan
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>

<?php include 'footer.php'; ?> <!-- Menyertakan footer.php untuk bagian footer halaman -->

<script src="js/script.js"></script> <!-- Link ke file JavaScript kustom -->

</body>
</html>
