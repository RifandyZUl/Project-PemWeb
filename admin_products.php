<?php

// Menyertakan file konfigurasi yang berisi pengaturan koneksi database
include 'config.php'; 

// Memulai sesi atau melanjutkan sesi yang sudah ada
session_start(); 

// Mengambil ID admin dari sesi, jika ada
$admin_id = $_SESSION['admin_id'] ?? null; 

// Mengecek apakah admin_id sudah diset dalam sesi
if (!isset($admin_id)) {
    // Jika admin_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Cek apakah formulir untuk menambahkan produk telah disubmit
if (isset($_POST['add_product'])) {
    // Mengambil dan membersihkan data dari formulir
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    // Memeriksa apakah file gambar ada dan tidak ada kesalahan saat mengupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image;

        // Cek apakah produk sudah ada di database
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
        $select_products->execute([$name]);

        if ($select_products->rowCount() > 0) {
            $message[] = 'Product name already exists!';
        } else {
            // Menyimpan produk baru ke database
            $insert_products = $conn->prepare("INSERT INTO `products` (name, category, details, price, image) VALUES (?, ?, ?, ?, ?)");
            $insert_products->execute([$name, $category, $details, $price, $image]);

            if ($insert_products) {
                // Mengecek ukuran file gambar
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large!';
                } else {
                    // Memindahkan file gambar ke folder yang diinginkan
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'New product added!';
                }
            }
        }
    } else {
        $message[] = 'No image file uploaded or there was an error!';
    }
}

// Menghapus produk berdasarkan ID yang dikirim melalui GET
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Mengambil nama gambar dari produk yang akan dihapus
    $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
    $select_delete_image->execute([$delete_id]);
    $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC); 

    // Menghapus file gambar dari server
    unlink('uploaded_img/' . $fetch_delete_image['image']);

    // Menghapus produk dari tabel products
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);

    // Menghapus produk dari tabel wishlist dan cart jika ada
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);

    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);

    // Mengalihkan kembali ke halaman admin_products.php setelah penghapusan
    header('location:admin_products.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">
</head>
<body>
    
    <!-- Menyertakan header admin -->
    <?php include 'admin_header.php'; ?>

    <!-- Bagian untuk menambahkan produk baru -->
    <section class="add-products">
        <h1 class="tittle">Add New Products</h1>

        <!-- Formulir untuk menambahkan produk -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <!-- Input untuk nama produk -->
                    <input type="text" name="name" class="box" required placeholder="Enter product name">
                    <!-- Dropdown untuk memilih kategori produk -->
                    <select name="category" class="box" required>
                        <option value="" selected disabled>Select Category</option>
                        <option value="vegetables">Vegetables</option>
                        <option value="fruits">Fruits</option>
                        <option value="meat">Meat</option>
                        <option value="fish">Fish</option>
                    </select>
                </div>
                
                <div class="inputBox">
                    <!-- Input untuk harga produk -->
                    <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
                    <!-- Input untuk mengupload gambar produk -->
                    <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
                </div>
            </div>
            <!-- Textarea untuk detail produk -->
            <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
            <!-- Tombol untuk menambahkan produk -->
            <input type="submit" class="btn" value="Add Product" name="add_product">
        </form>
    </section>

    <!-- Bagian untuk menampilkan produk yang sudah ditambahkan -->
    <section class="show-products">
        <h1 class="tittle">Products Added</h1>

        <div class="box-container">
            <?php
            // Menampilkan semua produk dari database
            $show_products = $conn->prepare("SELECT * FROM `products`");
            $show_products->execute();
            
            if ($show_products->rowCount() > 0) {
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <!-- Menampilkan informasi produk -->
            <div class="box">
                <div class="price">Rp<?= htmlspecialchars($fetch_products['price']); ?>/-</div>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                <div class="cat"><?= htmlspecialchars($fetch_products['category']); ?></div>
                <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
                <div class="flex-btn">
                    <!-- Tombol untuk memperbarui dan menghapus produk -->
                    <a href="admin_update_product.php?update=<?= htmlspecialchars($fetch_products['id']); ?>" class="option-btn">Update</a>
                    <a href="admin_products.php?delete=<?= htmlspecialchars($fetch_products['id']); ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <!-- Link ke file JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>
