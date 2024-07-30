<?php

// Menyertakan file konfigurasi untuk koneksi database
include 'config.php'; 

// Memulai sesi atau melanjutkan sesi yang sudah ada
session_start(); 

// Mengambil ID admin dari sesi saat ini
$admin_id = $_SESSION['admin_id'] ?? null; 

// Mengecek apakah admin_id sudah diset dalam sesi
if (!isset($admin_id)) {
    // Jika admin_id tidak diset, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit(); // Menghentikan eksekusi script setelah pengalihan
}

// Mengambil data profil admin dari database
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update_profile'])) {
    // Mengambil dan menyaring input dari form
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Mengupdate data profil admin
    $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $admin_id]);

    // Mengelola gambar profil
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $old_image = $_POST['old_image'];

    if (!empty($image)) {
        if ($image_size > 2000000) {
            // Menampilkan pesan jika ukuran gambar terlalu besar
            $message[] = 'Image size is too large!';
        } else {
            // Mengupdate gambar profil
            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $admin_id]);
            if ($update_image) {
                // Memindahkan file gambar ke folder yang ditentukan
                move_uploaded_file($image_tmp_name, $image_folder);
                // Menghapus gambar lama dari folder
                unlink('uploaded_img/' . $old_image);
                $message[] = 'Image updated successfully!';
            }
        }
    }

    // Mengelola password
    $old_pass = $_POST['old_pass'];
    $update_pass = $_POST['update_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if (!empty($update_pass) && !empty($new_pass) && !empty($confirm_pass)) {
        // Memverifikasi password lama
        if (!password_verify($old_pass, $fetch_profile['password'])) {
            $message[] = 'Old password does not match!';
        } elseif ($new_pass !== $confirm_pass) {
            // Memeriksa apakah password baru dan konfirmasi password cocok
            $message[] = 'New password and confirm password do not match!';
        } else {
            // Meng-hash password baru dan memperbarui password di database
            $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass_query->execute([$hashed_new_pass, $admin_id]);
            $message[] = 'Password updated successfully!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Profile</title> <!-- Judul halaman untuk admin -->

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/components.css">

</head>
<body>
    
<!-- Menyertakan header admin -->
<?php include 'admin_header.php'; ?>

<section class="update-profile">
    <!-- Judul section untuk pembaruan profil -->
    <h1 class="tittle">Update Profile</h1>

    <!-- Form untuk mengupdate profil admin -->
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- Menampilkan gambar profil saat ini -->
        <img src="images/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="Profile Picture">
        <div class="flex">
            <div class="inputBox">
                <!-- Input untuk username -->
                <span>Username :</span>
                <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>" placeholder="Update username" required class="box">
                <!-- Input untuk email -->
                <span>Email :</span>
                <input type="email" name="email" value="<?= htmlspecialchars($fetch_profile['email']); ?>" placeholder="Update email" required class="box">
                <!-- Input untuk gambar profil baru -->
                <span>Update Picture :</span>
                <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
            </div>
            <div class="inputBox">
                <!-- Input hidden untuk menyimpan password lama -->
                <input type="hidden" name="old_pass" value="<?= htmlspecialchars($fetch_profile['password']); ?>">
                <!-- Input untuk password lama -->
                <span>Old Password :</span>
                <input type="password" name="update_pass" placeholder="Enter previous password" class="box">
                <!-- Input hidden untuk menyimpan gambar lama -->
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_profile['image']); ?>">
                <!-- Input untuk password baru -->
                <span>New Password :</span>
                <input type="password" name="new_pass" placeholder="Enter new password" class="box">
                <!-- Input untuk konfirmasi password baru -->
                <span>Confirm Password :</span>
                <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
            </div>
        </div>
        <div class="flex-btn">
            <!-- Tombol untuk submit form -->
            <input type="submit" class="btn" value="Update Profile" name="update_profile">
            <!-- Tombol untuk kembali ke halaman admin -->
            <a href="admin_page.php" class="option-btn">Go Back</a>
        </div>
    </form>
</section>

<!-- Link ke file JavaScript -->
<script src="js/script.js"></script>

</body>
</html>
