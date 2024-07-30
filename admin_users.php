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
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ? "); // Mempersiapkan statement SQL untuk menghapus user berdasarkan ID
    $delete_users->execute([$delete_id]); // Menjalankan statement SQL dengan parameter ID yang diberikan
    header('location:admin_users.php'); // Mengarahkan kembali ke halaman admin_users.php setelah menghapus user
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">
</head>
<body>
    
<?php include 'admin_header.php'; // Menyertakan file header admin ?>

<section class="user-accounts">

    <h1 class="tittle">User Accounts</h1>

    <div class="box-container">

        <?php
            // Mempersiapkan statement SQL untuk memilih semua user
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute(); // Menjalankan statement SQL
            while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){ // Mengambil data user satu per satu
        ?>

        <!-- Membuat div untuk setiap user -->
        <div class="box" style="<?php if($fetch_users['id'] == $admin_id){echo 'display:none'; };?>">
            <!-- Menampilkan gambar user -->
            <img src="uploaded_img/<?= $fetch_users['image']; ?>" alt="">
            <!-- Menampilkan ID user -->
            <p> User Id : <span><?= $fetch_users['id']; ?></span></p>
            <!-- Menampilkan nama user -->
            <p> Username : <span><?= $fetch_users['name']; ?></span></p>
            <!-- Menampilkan email user -->
            <p> Email : <span><?= $fetch_users['email']; ?></span></p>
            <!-- Menampilkan tipe user dan mengubah warna jika user adalah admin -->
            <p> User Type : <span style="color:<?php if($fetch_users['user_type'] == 'admin'){echo 'red';}; ?>"><?= $fetch_users['user_type']; ?></span></p>
            <!-- Link untuk menghapus user dengan konfirmasi -->
            <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('delete this users?');" class="delete-btn">delete</a>
        </div>
        <?php
            }
        ?>

    </div>

<section>

<!-- Menyertakan file JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
