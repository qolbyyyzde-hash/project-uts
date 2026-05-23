<?php
include 'config.php';
session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // 1. Cek dulu apakah username ini sudah pernah dipakai orang lain
    $cek_query = "SELECT * FROM users WHERE username = '$username'";
    $cek_result = mysqli_query($conn, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        // Jika username sudah ada di database
        echo "<script>alert('Username sudah terdaftar! Silakan gunakan nama lain.'); window.location='register.php';</script>";
        exit;
    } else {
        // 2. Amankan password menggunakan password_hash sebelum disimpan
        $password_aman = password_hash($password, PASSWORD_DEFAULT);

        // 3. Masukkan data user baru ke database (INSERT)
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password_aman')";
        
        if (mysqli_query($conn, $insert_query)) {
            // Jika sukses menyimpan, langsung lempar ke halaman login.php
            echo "<script>alert('Pendaftaran akun sukses! Silakan login.'); window.location='login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mendaftar, terjadi kesalahan pada database.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Diary</title>
    <style>
        /* --- CSS untuk Tombol Social Login --- */
.social-separator {
    text-align: center;
    margin: 20px 0;
    color: #aaa;
    font-size: 14px;
    position: relative;
}
.social-separator::before, .social-separator::after {
    content: "";
    position: absolute;
    top: 50%;
    width: 30%;
    height: 1px;
    background-color: #e0e0e0;
}
.social-separator::before { left: 0; }
.social-separator::after { right: 0; }

.social-btn-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    transition: background 0.3s ease, transform 0.1s ease;
    border: 1px solid transparent;
    cursor: pointer;
}

.btn-social:active {
    transform: scale(0.98);
}

/* Warna & Gaya spesifik tiap platform */
.btn-google {
    background-color: #ffffff;
    color: #333333;
    border-color: #e0e0e0;
}
.btn-google:hover { background-color: #f7f7f7; }

.btn-facebook {
    background-color: #1877F2;
    color: #ffffff;
}
.btn-facebook:hover { background-color: #166FE5; }

.btn-apple {
    background-color: #000000;
    color: #ffffff;
}
.btn-apple:hover { background-color: #1a1a1a; }

/* Ukuran Icon */
.btn-social img {
    width: 18px;
    height: 18px;
    object-fit: contain;
}
        body { font-family: sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #5c67f2; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #4a55d4; }
    </style>
</head>
<body>
    <!-- Kotak Putih Utama (Card) -->
    <div class="card">
        <h2>Daftar Akun</h2>
        
        <!-- Form Pendaftaran -->
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <!-- Garis Pemisah Sosial Menu -->
        <div class="social-separator">atau daftar dengan</div>

        <!-- Tombol Media Sosial -->
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

        <!-- Link Balik ke Halaman Login -->
        <p style="text-align: center; font-size: 14px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        
    </div> <!-- Penutup class="card" yang krusial agar tidak bertumpuk -->
</body>
</html>