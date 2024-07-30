<?php
// Menyertakan file konfigurasi, hilangkan @ untuk menangani kesalahan secara eksplisit
include 'config.php'; 

session_start(); // Memulai sesi atau melanjutkan sesi yang sudah ada

// Mengambil ID admin dari sesi
$admin_id = $_SESSION['admin_id'] ?? null; 

// Mengecek apakah admin_id sudah diset dalam sesi
if (!isset($admin_id)) {
    // Jika admin_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Mengecek apakah ada parameter 'delete' dalam URL
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete']; // Mengambil nilai parameter 'delete' dari URL
    $delete_admins = $conn->prepare("DELETE FROM `users` WHERE id = ? AND user_type = 'admin'"); // Mempersiapkan statement SQL untuk menghapus admin berdasarkan ID
    $delete_admins->execute([$delete_id]); // Menjalankan statement SQL dengan parameter ID yang diberikan
    header('location:admin_admins.php'); // Mengarahkan kembali ke halaman admin_admins.php setelah menghapus admin
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">
</head>
<body>
    
<?php include 'admin_header.php'; // Menyertakan file header admin ?>

<section class="admin-accounts">

    <h1 class="tittle">Admin Accounts</h1>

    <div class="box-container">

        <?php
            // Mempersiapkan statement SQL untuk memilih semua admin
            $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = 'admin'");
            $select_admins->execute(); // Menjalankan statement SQL
            while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){ // Mengambil data admin satu per satu
        ?>

        <!-- Membuat div untuk setiap admin -->
        <div class="box">
            <!-- Menampilkan gambar admin -->
            <img src="uploaded_img/<?= $fetch_admins['image']; ?>" alt="">
            <!-- Menampilkan ID admin -->
            <p> Admin Id : <span><?= $fetch_admins['id']; ?></span></p>
            <!-- Menampilkan nama admin -->
            <p> Username : <span><?= $fetch_admins['name']; ?></span></p>
            <!-- Menampilkan email admin -->
            <p> Email : <span><?= $fetch_admins['email']; ?></span></p>
            <!-- Menampilkan tipe admin -->
            <p> User Type : <span style="color:red;"><?= $fetch_admins['user_type']; ?></span></p>
            <!-- Link untuk menghapus admin dengan konfirmasi -->
            <a href="admin_admins.php?delete=<?= $fetch_admins['id']; ?>" onclick="return confirm('delete this admin?');" class="delete-btn">delete</a>
        </div>
        <?php
            }
        ?>

    </div>

    <!-- Menambahkan div untuk menampilkan jumlah total admins -->
    <div class="box">
        <?php
            // Menghitung jumlah total admins
            $select_admins_count = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
            $select_admins_count->execute(['admin']);
            $number_of_admins = $select_admins_count->rowCount();
        ?>
    </div>

<section>

<!-- Menyertakan file JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
