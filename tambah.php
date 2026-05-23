<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "INSERT INTO notes (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menambahkan catatan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tulis Diary Baru</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f9; padding: 20px; }
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input[type="text"], textarea { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 200px; resize: vertical; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-submit { background: #5c67f2; color: white; }
        .btn-cancel { background: #6c757d; color: white; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tulis Cerita Hari Ini</h2>
        <form action="" method="POST">
            <label for="title">Judul Catatan</label>
            <input type="text" id="title" name="title" placeholder="Masukkan judul..." required>

            <label for="content">Isi Cerita</label>
            <textarea id="content" name="content" placeholder="Tuliskan isi diary Anda di sini..." required></textarea>

            <div style="text-align: right;">
                <a href="index.php" class="btn btn-cancel">Batal</a>
                <button type="submit" name="simpan" class="btn btn-submit">Simpan Catatan</button>
            </div>
        </form>
    </div>
</body>
</html>