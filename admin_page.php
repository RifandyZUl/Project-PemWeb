<?php

// Menyertakan file konfigurasi
include 'config.php'; 

// Memulai sesi atau melanjutkan sesi yang sudah ada
session_start(); 

// Mengambil ID admin dari sesi
$admin_id = $_SESSION['admin_id'] ?? null; 

// Mengecek apakah admin_id sudah diset dalam sesi
if (!isset($admin_id)) {
    // Jika admin_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">

</head>
<body>
    
    <!-- Menyertakan header admin -->
    <?php include 'admin_header.php'; ?>

    <!-- Bagian dasbor -->
    <section class="dashboard">
        <h1 class="tittle">Dashboard</h1>

        <div class="box-container">
            <!-- Kotak untuk total pendings -->
            <div class="box">
                <?php
                    // Menghitung total pendings
                    $total_pendings = 0;
                    $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                    $select_pendings->execute(['pending']);
                    while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                        $total_pendings += $fetch_pendings['total_price'];
                    }
                ?>
                <h3>Rp<?= number_format($total_pendings, 0, ',', '.'); ?>.000</h3> <!-- Format uang -->
                <p>Total Pendings</p>
                <a href="admin_orders.php" class="btn">See Orders</a>
            </div>

            <!-- Kotak untuk total completed orders -->
            <div class="box">
                <?php
                    // Menghitung total completed orders
                    $total_complated = 0;
                    $select_complated = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                    $select_complated->execute(['completed']);
                    while($fetch_complated = $select_complated->fetch(PDO::FETCH_ASSOC)) {
                        $total_complated += $fetch_complated['total_price'];
                    }
                ?>
                <h3>Rp<?= number_format($total_complated, 0, ',', '.'); ?>.000</h3> <!-- Format uang -->
                <p>Completed Orders</p>
                <a href="admin_orders.php" class="btn">See Orders</a>
            </div>

            <!-- Kotak untuk total orders placed -->
            <div class="box">
                <?php
                    // Menghitung jumlah total orders
                    $select_orders = $conn->prepare("SELECT * FROM `orders`");
                    $select_orders->execute();
                    $number_of_orders = $select_orders->rowCount();
                ?>
                <h3><?= $number_of_orders; ?></h3>
                <p>Orders Placed</p>
                <a href="admin_orders.php" class="btn">See Orders</a>
            </div>

            <!-- Kotak untuk total products added -->
            <div class="box">
                <?php
                    // Menghitung jumlah total produk
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                    $number_of_products = $select_products->rowCount();
                ?>
                <h3><?= $number_of_products; ?></h3>
                <p>Products Added</p>
                <a href="admin_products.php" class="btn">See Products</a>
            </div>

            <!-- Kotak untuk total users -->
            <div class="box">
                <?php
                    // Menghitung jumlah total users
                    $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
                    $select_users->execute(['user']);
                    $number_of_users = $select_users->rowCount();
                ?>
                <h3><?= $number_of_users; ?></h3>
                <p>Total Users</p>
                <a href="admin_users.php" class="btn">See Accounts</a>
            </div>

            <!-- Kotak untuk total admins -->
            <div class="box">
                <?php
                    // Menghitung jumlah total admins
                    $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
                    $select_admins->execute(['admin']);
                    $number_of_admins = $select_admins->rowCount();
                ?>
                <h3><?= $number_of_admins; ?></h3>
                <p>Total Admins</p>
                <a href="admin_admins.php" class="btn">See Accounts</a>
            </div>

            <!-- Kotak untuk total accounts -->
            <div class="box">
                <?php
                    // Menghitung jumlah total accounts
                    $select_accounts = $conn->prepare("SELECT * FROM `users`");
                    $select_accounts->execute();
                    $number_of_accounts = $select_accounts->rowCount();
                ?>
                <h3><?= $number_of_accounts; ?></h3>
                <p>Total Accounts</p>
                <a href="admin_users.php" class="btn">See Users</a>
            </div>

            <!-- Kotak untuk total messages -->
            <div class="box">
                <?php
                    // Menghitung jumlah total messages
                    $select_messages = $conn->prepare("SELECT * FROM `message`");
                    $select_messages->execute();
                    $number_of_messages = $select_messages->rowCount();
                ?>
                <h3><?= $number_of_messages; ?></h3>
                <p>Total Messages</p>
                <a href="admin_contacts.php" class="btn">See Messages</a>
            </div>
        </div>
    </section>

    <script src="js/script.js"></script>
</body>
</html>
