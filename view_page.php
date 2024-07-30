<?php

@include 'config.php'; // Menyertakan file konfigurasi untuk pengaturan koneksi database
session_start(); // Memulai atau melanjutkan sesi PHP

// Mengambil user_id dari sesi
$user_id = $_SESSION['user_id'] ?? null;

// Mengecek apakah user_id ada dalam sesi
if (!isset($user_id)) {
    header('Location: login.php'); // Mengarahkan pengguna ke halaman login jika tidak ada user_id
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Proses untuk menambahkan produk ke wishlist
if (isset($_POST['add_to_wishlist'])) {
    // Mengambil dan membersihkan data dari formulir
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

    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'Already added to wishlist!';
    } elseif ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'Already added to cart!';
    } else {
        // Menambahkan produk ke wishlist
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
        $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
        $message[] = 'Added to wishlist!';
    }
}

// Proses untuk menambahkan produk ke cart
if (isset($_POST['add_to_cart'])) {
    // Mengambil dan membersihkan data dari formulir
    $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

    // Mengecek apakah produk sudah ada di cart
    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'Already added to cart!';
    } else {
        // Mengecek apakah produk sudah ada di wishlist dan menghapus jika ada
        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        if ($check_wishlist_numbers->rowCount() > 0) {
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$p_name, $user_id]);
        }

        // Menambahkan produk ke cart
        $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
        $message[] = 'Added to cart!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick View</title> <!-- Judul halaman yang lebih sesuai -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Menyertakan header -->
    <?php include 'header.php'; ?>

    <!-- Bagian untuk melihat produk secara cepat -->
    <section class="quick-view">
        <h1 class="tittle">Quick View</h1>

        <?php
        // Mengambil ID produk dari URL
        $pid = $_GET['pid'];
        
        // Mengambil detail produk dari database
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_products->execute([$pid]);
        
        if ($select_products->rowCount() > 0) {
            // Menampilkan detail produk
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" class="box" method="POST">
            <div class="price">Rp<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
            <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
            <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
            <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
            <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
            <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
            <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
            <input type="number" min="1" value="1" name="p_qty" class="qty">
            <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist">
            <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
        </form>
        <?php
            }
        } else {
            // Pesan jika tidak ada produk
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>

    </section>

    <!-- Menyertakan footer -->
    <?php include 'footer.php'; ?>

    <!-- Link ke file JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>
