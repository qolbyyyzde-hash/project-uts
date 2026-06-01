<?php
include 'config.php';
session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // PINTU RAHASIA EMERGENCY: Kalau ketik ini, langsung lolos tanpa peduli isi database
    if ($username === 'qotby' && $password === '123') {
        $_SESSION['username'] = 'qotby';
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }

    // Jalur Verifikasi Database Normal
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            echo "<script>window.location.href='index.php';</script>";
            exit;
        } else {
            $error_message = "Username atau password salah!";
        }
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
        body { font-family: sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #5c67f2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #4a55d4; }
        .error { color: red; font-size: 14px; text-align: center; margin-bottom: 10px; font-weight: bold; }
        
        /* CSS Tombol Sosial Menu */
        .social-separator { text-align: center; margin: 20px 0; color: #aaa; font-size: 14px; position: relative; }
        .social-separator::before, .social-separator::after { content: ""; position: absolute; top: 50%; width: 25%; height: 1px; background-color: #e0e0e0; }
        .social-separator::before { left: 0; }
        .social-separator::after { right: 0; }
        .social-btn-container { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
        .btn-social { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; color: #333; border: 1px solid #e0e0e0; }
        .btn-google { background-color: #fff; }
        .btn-facebook { background-color: #1877F2; color: #fff; border: none; }
        .btn-apple { background-color: #000; color: #fff; border: none; }
        .btn-social img { width: 18px; height: 18px; object-fit: contain; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Login Diary</h2>
        
        <?php if (!empty($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
        
        <div class="social-separator">atau masuk dengan</div>

        <div class="social-btn-container">
            <a href="#" class="btn-social btn-google">
                <img src="https://fonts.gstatic.com/s/i/productlogos/googleg/v6/web-24dp/logo_googleg_24dp.svg" alt="Google"> Google
            </a>
            <a href="#" class="btn-social btn-facebook">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b9/2023_Facebook_icon.svg" alt="Facebook"> Facebook
            </a>
            <a href="#" class="btn-social btn-apple">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" style="filter: invert(1);" alt="Apple"> Apple ID
            </a>
        </div>
        
        <p style="font-size: 14px; text-align: center; margin-top: 15px;">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </p>

        <p style="text-align: center; margin-top: 20px; font-size: 13px;">
            <a href="index.php" style="color: #5c67f2; text-decoration: none; font-weight: bold;"></a>
        </p>
    </div>

</body>
</html>