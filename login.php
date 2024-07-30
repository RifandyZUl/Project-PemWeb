<?php

session_start(); // Memulai sesi

// Menyertakan file konfigurasi yang mungkin berisi pengaturan koneksi database
include 'config.php';


// Mengecek apakah form telah disubmit
if(isset($_POST['submit'])){

    // Mengambil data dari form dan melakukan sanitasi input untuk keamanan
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Menghapus karakter berbahaya dari email
    $pass = $_POST['pass']; // Mengambil password dari form

    // Mengecek apakah email sesuai dengan data di database
    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($row){
        // Verifikasi password
        if(password_verify($pass, $row['password'])){
            // Menyimpan ID pengguna dalam sesi dan mengarahkan ke halaman yang sesuai
            if($row['user_type'] == 'admin'){
                $_SESSION['admin_id'] = $row['id'];
                header('location:admin_page.php');
                exit();
            } elseif($row['user_type'] == 'user'){
                $_SESSION['user_id'] = $row['id'];
                header('location:home.php');
                exit();
            } else {
                $message[] = 'No user type found!';
            }
        } else {
            $message[] = 'Incorrect email or password!';
        }
    } else {
        $message[] = 'Incorrect email or password!';
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Font Awesome CDN Link untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Link CSS untuk gaya halaman -->
    <link rel="stylesheet" href="CSS/components.css">
</head>
<body>

<?php
// Menampilkan pesan-pesan jika ada
if(isset($message)){
    foreach($message as $msg){
        echo '
        <div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

    <section class="form-container">
        <!-- Formulir login -->
        <form action="" method="POST">
            <h3>Login Now</h3>
            <!-- Input untuk email pengguna -->
            <input type="email" name="email" class="box" placeholder="Enter your email" required>
            <!-- Input untuk password pengguna -->
            <input type="password" name="pass" class="box" placeholder="Enter your password" required>
            <!-- Tombol submit form -->
            <input type="submit" value="Login Now" class="btn" name="submit">
            <!-- Link ke halaman registrasi jika pengguna belum punya akun -->
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </section>
</body>
</html>

