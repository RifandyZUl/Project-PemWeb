<?php

include 'config.php'; // Menyertakan file konfigurasi yang berisi pengaturan koneksi database
session_start(); // Memulai sesi atau melanjutkan sesi yang sudah ada

// Mengambil ID pengguna dari sesi, jika ada
$user_id = $_SESSION['user_id'];

// Mengecek apakah user_id sudah diset dalam sesi
if (!isset($user_id)) {
    // Jika user_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Proses untuk menambahkan produk ke wishlist
if (isset($_POST['add_to_wishlist'])) {
    // Mengambil dan membersihkan data dari formulir
    $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
    $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
    $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
    
    // Cek apakah produk sudah ada di wishlist
    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);
    
    // Cek apakah produk sudah ada di cart
    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'Already added to wishlist!';
    } elseif ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'Already added to cart!';
    } else {
        // Tambahkan produk ke wishlist
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (user_id, name, price, image) VALUES (?, ?, ?, ?)");
        $insert_wishlist->execute([$user_id, $p_name, $p_price, $p_image]);
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

    // Cek apakah produk sudah ada di cart
    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        $message[] = 'Already added to cart!';
    } else {
        // Hapus produk dari wishlist jika ada
        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        if ($check_wishlist_numbers->rowCount() > 0) {
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$p_name, $user_id]);
        }

        // Tambahkan produk ke cart
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title> <!-- Judul halaman yang lebih sesuai -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    
    <!-- Menyertakan header -->
    <?php include 'header.php'; ?>

    <!-- Bagian utama halaman dengan latar belakang -->
    <div class="home-bg">
        <section class="home">
            <div class="content">
                <span>Don't Panic, Go Organic</span>
                <h3>Quality Fresh Produce, Delivered with Love</h3>
                <p>Komitmen kami terhadap kesegaran dan kualitas memastikan setiap produk yang Anda terima adalah yang terbaik.</p>
                <a href="about.php" class="home-btn">About Us</a>
            </div>
        </section>
    </div>

    <!-- Bagian kategori produk -->
    <section class="home-category">
        <h1 class="tittle">Shop By Category</h1>
        <div class="box-container">
            <!-- Kategori buah-buahan -->
            <div class="box">
                <img src="images/cat-1.png" alt="Fruits">
                <h3>Fruits</h3>
                <p>"Temukan buah-buahan segar dan lezat kami yang dipanen pada puncak kematangan. Pilihan kami mencakup berbagai jenis buah, dari apel segar hingga berry eksotis, menawarkan rasa manis alami dan manfaat kesehatan yang optimal."</p>
                <a href="category.php?category=fruits" class="btn">Fruits</a>
            </div>
            <!-- Kategori daging -->
            <div class="box">
                <img src="images/cat-2.png" alt="Meat">
                <h3>Meat</h3>
                <p>"Pilih daging berkualitas tinggi kami untuk pengalaman kuliner yang luar biasa. Dari steak empuk hingga ayam yang penuh rasa, setiap potongan dipilih secara teliti untuk memenuhi standar tertinggi dan memastikan kepuasan di setiap hidangan."</p>
                <a href="category.php?category=meat" class="btn">Meat</a>
            </div>
            <!-- Kategori sayuran -->
            <div class="box">
                <img src="images/cat-3.png" alt="Vegetables">
                <h3>Vegetables</h3>
                <p>"Temukan sayuran segar dan berkualitas tinggi kami, ideal untuk menambahkan rasa dan nutrisi pada setiap hidangan Anda. Dari daun hijau segar yang menyehatkan, semua hasil kami dipilih langsung dari petani lokal untuk memastikan kualitas terbaik."</p>
                <a href="category.php?category=vegetables" class="btn">Vegetables</a>
            </div>
            <!-- Kategori ikan -->
            <div class="box">
                <img src="images/cat-4.png" alt="Fish">
                <h3>Fish</h3>
                <p>"Nikmati pilihan ikan segar premium kami, termasuk salmon, trout, dan jenis lainnya yang dipilih dengan cermat untuk kualitas dan rasa terbaik. Ikan kami cocok untuk berbagai resep, memberikan kesegaran dan manfaat nutrisi di setiap gigitan."</p>
                <a href="category.php?category=fish" class="btn">Fish</a>
            </div>
        </div>
    </section>

    <!-- Bagian produk terbaru -->
    <section class="products">
        <h1 class="tittle">Latest Products</h1>
        <div class="box-container">
            <?php
                // Mengambil produk terbaru dari database
                $select_products = $conn->prepare("SELECT * FROM `products`");
                $select_products->execute();
                if ($select_products->rowCount() > 0) {
                    // Menampilkan setiap produk
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form action="" class="box" method="POST">
                <div class="price">Rp<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
                <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
                <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
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
        </div>
    </section>

    <!-- Menyertakan footer -->
    <?php include 'footer.php'; ?>

    <!-- Link ke file JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>
