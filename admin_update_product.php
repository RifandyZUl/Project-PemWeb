<?php

// Menyertakan file konfigurasi untuk menghubungkan ke database
include 'config.php'; 

// Memulai sesi atau melanjutkan sesi yang sudah ada
session_start(); 

// Mengambil ID admin dari sesi jika ada
$admin_id = $_SESSION['admin_id'] ?? null; 

// Mengecek apakah admin_id sudah diset dalam sesi
if (!isset($admin_id)) {
    // Jika admin_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Mengecek apakah formulir pembaruan produk telah disubmit
if(isset($_POST['update_product'])){

    // Mengambil data dari formulir
    $pid = $_POST['pid'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $old_image = $_POST['old_image'];

    // Update data produk tanpa gambar
    $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
    $update_product->execute([$name, $category, $details, $price, $pid]);

    // Menyimpan pesan keberhasilan
    $message[] = 'Product updated successfully!';

    // Jika ada gambar baru yang diupload
    if(!empty($image)){
        // Cek ukuran gambar
        if($image_size > 2000000){
            $message[] = 'Image size is too large!';
        } else {
            // Update gambar produk di database
            $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $pid]);

            // Jika gambar berhasil diupdate
            if($update_image){
                // Pindahkan file gambar baru ke folder yang diinginkan
                move_uploaded_file($image_tmp_name, $image_folder);
                // Hapus gambar lama jika ada
                if (file_exists('uploaded_img/'.$old_image)) {
                    unlink('uploaded_img/'.$old_image);
                }
                $message[] = 'Image updated successfully!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">

</head>
<body>
    
<?php include 'admin_header.php'; // Menyertakan header admin ?>

<section class="update-product">
    <h1 class="tittle">Update Product</h1>

    <?php
    // Mengambil ID produk dari URL
    $update_id = $_GET['update'];
    // Mengambil data produk berdasarkan ID dari database
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_products->execute([$update_id]);

    // Mengecek apakah produk ditemukan
    if($select_products->rowCount() > 0){
        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
    ?>
    <!-- Formulir untuk memperbarui produk -->
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Menyimpan nama gambar lama secara tersembunyi -->
        <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
        <!-- Menyimpan ID produk secara tersembunyi -->
        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
        <!-- Menampilkan gambar produk saat ini -->
        <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" style="max-width: 200px; height: auto;">
        <!-- Input untuk nama produk -->
        <input type="text" name="name" placeholder="Enter product name" required class="box" value="<?= $fetch_products['name']; ?>">
        <!-- Input untuk harga produk -->
        <input type="number" name="price" min="0" placeholder="Enter product price" required class="box" value="<?= $fetch_products['price']; ?>">
        <!-- Pilih kategori produk -->
        <select name="category" class="box" required>
            <option value="<?= $fetch_products['category']; ?>" selected><?= ucfirst($fetch_products['category']); ?></option>
            <option value="vegetables">Vegetables</option>
            <option value="fruits">Fruits</option>
            <option value="meat">Meat</option>
            <option value="fish">Fish</option>
        </select>
        <!-- Input untuk detail produk -->
        <textarea name="details" placeholder="Enter product details" class="box" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
        <!-- Input untuk gambar baru -->
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
        <!-- Tombol untuk menyimpan perubahan dan kembali ke halaman sebelumnya -->
        <div class="flex-btn">
            <input type="submit" class="btn" value="Update Product" name="update_product">
            <a href="admin_products.php" class="option-btn">Go Back</a>
        </div>
    </form>
    <?php
        }   
    } else {
        // Menampilkan pesan jika tidak ada produk yang ditemukan
        echo '<p class="empty">No products found!</p>';
    }
    ?>
</section>

<script src="js/script.js"></script> <!-- Link ke file JavaScript -->

</body>
</html>
