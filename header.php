<?php

// Menampilkan pesan-pesan jika ada
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message">
            <span>'.$message.'</span> <!-- Menampilkan pesan -->
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i> <!-- Ikon untuk menutup pesan -->
        </div>
        ';
    }
}
?>

<header class="header"> <!-- Mulai elemen header -->

    <div class="flex"> <!-- Kontainer fleksibel untuk header -->

        <!-- Link ke halaman utama Admin -->
        <a href="admin_page.php" class="logo">Fresh<span>Harvest</span></a>

        <!-- Navbar dengan link ke halaman-halaman lainnya -->
        <nav class="navbar">
            <a href="home.php">Home</a> <!-- Link ke halaman Home -->
            <a href="shop.php">Shop</a> <!-- Link ke halaman Shop -->
            <a href="orders.php">Orders</a> <!-- Link ke halaman Orders -->
            <a href="about.php">About</a> <!-- Link ke halaman About -->
            <a href="contact.php">Contacts</a> <!-- Link ke halaman Contacts -->
        </nav>

        <div class="icons"> <!-- Kontainer untuk ikon -->
            <div id="menu-btn" class="fas fa-bars"></div> <!-- Ikon menu (hamburger) -->
            <div id="user-btn" class="fas fa-user"></div> <!-- Ikon profil pengguna -->
            <a href="search_page.php" class="fas fa-search"></a> <!-- Ikon pencarian -->
            <?php
            // Menghitung jumlah item di cart
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);

            // Menghitung jumlah item di wishlist
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            ?>
            <!-- Menampilkan jumlah item di wishlist -->
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
            <!-- Menampilkan jumlah item di cart -->
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
        </div>

        <!-- Profil pengguna -->
        <div class="profile">
            <?php 
                // Menyiapkan dan mengeksekusi query untuk mengambil informasi profil pengguna dari database
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <!-- Menampilkan gambar profil pengguna dan nama -->
            <img src="images/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>

            <!-- Link untuk memperbarui profil dan logout -->
            <a href="user_update_profil.php" class="btn">update profile</a>
            <a href="login.php" class="delete-btn">logout</a>

            <!-- Opsi tambahan untuk login dan register (akan ditampilkan jika pengguna belum login) -->
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
        </div>
    </div>
</header>
