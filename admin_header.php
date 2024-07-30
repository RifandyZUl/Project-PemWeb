<?php
// Menampilkan pesan-pesan jika ada
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">
    <div class="flex">
        <!-- Link ke halaman admin utama -->
        <a href="admin_page.php" class="logo">Admin<span>panel</span></a>

        <!-- Navbar dengan link ke halaman-halaman admin lainnya -->
        <nav class="navbar">
            <a href="admin_page.php">Home</a>
            <a href="admin_products.php">Products</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_users.php">Users</a>
            <a href="admin_contacts.php">Messages</a>
        </nav>

        <!-- Ikon untuk menu dan pengguna -->
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <!-- Profil admin -->
        <div class="profile">
            <?php 
                // Menyiapkan dan mengeksekusi query untuk mengambil informasi profil admin dari database
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <!-- Menampilkan gambar profil dan nama admin -->
            <img src="images/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>

            <!-- Link untuk memperbarui profil dan logout -->
            <a href="admin_update_profile.php" class="btn">update profile</a>
            <a href="login.php" class="delete-btn">logout</a>

            <!-- Opsi tambahan untuk login dan register -->
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
        </div>
    </div>
</header>
