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

// Mengecek apakah ada parameter 'delete' di URL
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Menghapus pesan dari database berdasarkan ID
    $delete_messages = $conn->prepare("DELETE FROM `message` WHERE id = ?");
    $delete_messages->execute([$delete_id]);
    
    // Mengalihkan kembali ke halaman admin_contacts setelah penghapusan
    header('Location: admin_contacts.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/admin_style.css">
</head>
<body>
    
    <?php include 'admin_header.php'; ?>

    <section class="messages">
        <h1 class="tittle">User Accounts</h1>

        <div class="box-container">
        <?php
            // Mengambil semua pesan dari database
            $select_messages = $conn->prepare("SELECT * FROM `message`");
            $select_messages->execute();
            
            // Mengecek jika ada pesan dan menampilkannya
            if ($select_messages->rowCount() > 0) {
                while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>User Id: <span><?= htmlspecialchars($fetch_messages['user_id']); ?></span></p>
            <p>Name: <span><?= htmlspecialchars($fetch_messages['name']); ?></span></p>
            <p>Number: <span><?= htmlspecialchars($fetch_messages['number']); ?></span></p>
            <p>Email: <span><?= htmlspecialchars($fetch_messages['email']); ?></span></p>
            <p>Message: <span><?= htmlspecialchars($fetch_messages['message']); ?></span></p>
            <a href="admin_contacts.php?delete=<?= htmlspecialchars($fetch_messages['id']); ?>" 
               onclick="return confirm('Delete this message?');" 
               class="delete-btn">Delete</a>
        </div>
        <?php
                }
            } else {
                // Menampilkan pesan jika tidak ada data
                echo '<p class="empty">No messages found!</p>';
            }
        ?>
        </div>
    </section>

    <script src="js/script.js"></script>

</body>
</html>
