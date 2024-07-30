<?php

// Menyertakan file konfigurasi yang mungkin berisi pengaturan koneksi database
include 'config.php';

// Mengecek apakah form telah disubmit
if(isset($_POST['submit'])){

    // Mengambil data dari form dan melakukan sanitasi input untuk keamanan
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING); // Menghapus karakter berbahaya dari nama
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Menghapus karakter berbahaya dari email
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Validasi password
    if(strlen($pass) < 8 || !preg_match('/[A-Z]/', $pass) || !preg_match('/[0-9]/', $pass) || !preg_match('/[\W_]/', $pass)) {
        $message[] = 'Password harus memiliki minimal 8 karakter, termasuk huruf kapital, angka, dan simbol.';
    } elseif($pass !== $cpass) {
        $message[] = 'Confirm password does not match!'; // Menyimpan pesan jika password tidak cocok
    } else {
        $pass = password_hash($pass, PASSWORD_DEFAULT); // Mengenkripsi password menggunakan bcrypt

        // Mengambil informasi file gambar
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/'.$image; // Menentukan lokasi folder untuk menyimpan gambar yang diupload

        // Mengecek apakah email sudah ada di database
        $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select->execute([$email]);

        if($select->rowCount() > 0){
            $message[] = 'User email already exists!'; // Menyimpan pesan jika email sudah ada
        } else {
            // Menyimpan data pengguna baru ke database
            $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
            $insert->execute([$name, $email, $pass, $image]);

            if($insert){
                // Mengecek ukuran file gambar
                if($image_size > 2000000){
                    $message[] = 'Image size is too large!'; // Menyimpan pesan jika ukuran gambar terlalu besar
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder); // Memindahkan file gambar dari folder sementara ke folder tujuan
                    $message[] = 'Registered successfully!'; // Menyimpan pesan jika pendaftaran berhasil
                    header('location:login.php'); // Mengalihkan pengguna ke halaman login setelah berhasil mendaftar
                    exit();
                }
            }
        }
    }
}

?>
>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

<?php

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
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>register now</h3>
      <input type="text" name="name" class="box" placeholder="enter your name" required>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>


</body>
</html>