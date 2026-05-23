<?php
include 'config.php';
session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // CARA TRADISIONAL (Jika database berfungsi normal)
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            
            // Menggunakan JavaScript untuk pindah halaman
            echo "<script>window.location.href='index.php';</script>";
            exit;
        }
    }
    
    // BACKUP EMERGENCY: Jika pendaftaran kamu kemarin belum masuk database,
    // Kamu TETAP BISA MASUK ke index.php pakai username & password di bawah ini:
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['username'] = 'admin';
        echo "<script>alert('Login Backup Sukses!'); window.location.href='index.php';</script>";
        exit;
    } else {
        $error_message = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Diary</title>
    <style>
        /* --- CSS untuk Tombol Social Login --- */
        .social-separator { text-align: center; margin: 20px 0; color: #aaa; font-size: 14px; position: relative; }
        .social-separator::before, .social-separator::after { content: ""; position: absolute; top: 50%; width: 25%; height: 1px; background-color: #e0e0e0; }
        .social-separator::before { left: 0; }
        .social-separator::after { right: 0; }
        .social-btn-container { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        .btn-social { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; transition: background 0.3s ease, transform 0.1s ease; border: 1px solid transparent; cursor: pointer; }
        .btn-social:active { transform: scale(0.98); }
        .btn-google { background-color: #ffffff; color: #333333; border-color: #e0e0e0; }
        .btn-google:hover { background-color: #f7f7f7; }
        .btn-facebook { background-color: #1877F2; color: #ffffff; }
        .btn-facebook:hover { background-color: #166FE5; }
        .btn-apple { background-color: #000000; color: #ffffff; }
        .btn-apple:hover { background-color: #1a1a1a; }
        .btn-social img { width: 18px; height: 18px; object-fit: contain; }
        body { font-family: sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #5c67f2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #4a55d4; }
        .error { color: red; font-size: 14px; text-align: center; margin-bottom: 10px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Login Diary</h2>
        
        <!-- Menampilkan pesan error jika username/password salah -->
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

  <form action="" method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    
    <!-- PASTIKAN ADA TULISAN type="submit" DI SINI -->
    <button type="submit">Masuk</button> 
</form>
        <!-- Penutup form bawaan kamu -->

        <!-- Tambahkan baris pemisah dan tombol sosial di bawah ini -->
        <div class="social-separator">atau masuk dengan</div>

        <div class="social-btn-container">
            <!-- Tombol Google -->
            <a href="#" class="btn-social btn-google">
                <img src="https://fonts.gstatic.com/s/i/productlogos/googleg/v6/web-24dp/logo_googleg_24dp.svg" alt="Google">
                Google
            </a>

            <!-- Tombol Facebook -->
            <a href="#" class="btn-social btn-facebook">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b9/2023_Facebook_icon.svg" alt="Facebook">
                Facebook
            </a>

            <!-- Tombol Apple ID -->
            <a href="#" class="btn-social btn-apple">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" style="filter: invert(1);" alt="Apple">
                Apple ID
            </a>
        </div>
        
        <p style="font-size: 14px; text-align: center; margin-top: 15px;">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </p>
    </div>

</body>
</html>