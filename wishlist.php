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

// Mengecek jika parameter "delete" ada di URL untuk menghapus item dari wishlist.
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   // Menyiapkan query untuk menghapus item dari wishlist berdasarkan ID.
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$delete_id]);
   // Mengarahkan kembali ke halaman wishlist setelah penghapusan.
   header('location:wishlist.php');
   exit(); // Menghentikan eksekusi script setelah pengalihan.
}

// Mengecek jika parameter "delete_all" ada di URL untuk menghapus semua item dari wishlist.
if(isset($_GET['delete_all'])){

   // Menyiapkan query untuk menghapus semua item dari wishlist berdasarkan user_id.
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   // Mengarahkan kembali ke halaman wishlist setelah penghapusan.
   header('location:wishlist.php');
   exit(); // Menghentikan eksekusi script setelah pengalihan.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title> <!-- Judul halaman -->

   <!-- Link ke Font Awesome CDN untuk ikon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Link ke file CSS kustom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?> <!-- Menyertakan file header -->

<section class="wishlist"> <!-- Bagian untuk menampilkan wishlist pengguna -->

   <h1 class="tittle">products added</h1> <!-- Judul untuk daftar wishlist -->

   <div class="box-container"> <!-- Container untuk menampilkan setiap produk dalam wishlist -->

   <?php
      $grand_total = 0; // Inisialisasi variabel untuk menghitung total harga.
      // Menyiapkan query untuk memilih semua item dari wishlist berdasarkan user_id.
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);

      // Mengecek jika ada item di wishlist.
      if($select_wishlist->rowCount() > 0){
         // Mengambil dan menampilkan setiap item dalam wishlist.
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box"> <!-- Formulir untuk setiap item wishlist -->
      <!-- Tautan untuk menghapus item dari wishlist dengan konfirmasi. -->
      <a href="wishlist.php?delete=<?= $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
      <!-- Tautan untuk melihat detail produk. -->
      <a href="view_page.php?pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt=""> <!-- Gambar produk -->
      <div class="name"><?= $fetch_wishlist['name']; ?></div> <!-- Nama produk -->
      <div class="price">Rp<?= $fetch_wishlist['price']; ?>.000</div> <!-- Harga produk -->
      <input type="number" min="1" value="1" class="qty" name="p_qty"> <!-- Input untuk jumlah produk -->
      <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>"> <!-- ID produk (tidak terlihat) -->
      <input type="hidden" name="p_name" value="<?= $fetch_wishlist['name']; ?>"> <!-- Nama produk (tidak terlihat) -->
      <input type="hidden" name="p_price" value="<?= $fetch_wishlist['price']; ?>"> <!-- Harga produk (tidak terlihat) -->
      <input type="hidden" name="p_image" value="<?= $fetch_wishlist['image']; ?>"> <!-- Gambar produk (tidak terlihat) -->
      <input type="submit" value="add to cart" name="add_to_cart" class="btn"> <!-- Tombol untuk menambahkan produk ke cart -->
   </form>
   <?php
      // Menambahkan harga produk ke total harga.
      $grand_total += $fetch_wishlist['price'];
      }
   }else{
      // Jika tidak ada item di wishlist, tampilkan pesan kosong.
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <!-- Menampilkan total harga wishlist dan tautan untuk melanjutkan belanja atau menghapus semua item. -->
   <div class="wishlist-total">
      <p>grand total : <span>Rp<?= $grand_total; ?>.000</span></p>
      <a href="shop.php" class="option-btn">continue shopping</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
   </div>

</section>

<?php include 'footer.php'; ?> <!-- Menyertakan file footer -->

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
