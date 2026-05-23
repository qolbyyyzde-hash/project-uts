<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$note_id = mysqli_real_escape_string($conn, $_GET['id']);

// Pastikan catatan yang diedit adalah benar milik user yang sedang login
$check_note = mysqli_query($conn, "SELECT * FROM notes WHERE id = '$note_id' AND user_id = '$user_id'");
if (mysqli_num_rows($check_note) === 0) {
    echo "<script>alert('Akses ditolak atau catatan tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($check_note);

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "UPDATE notes SET title = '$title', content = '$content' WHERE id = '$note_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal memperbarui catatan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Diary</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f9; padding: 20px; }
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input[type="text"], textarea { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 200px; resize: vertical; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-submit { background: #ffc107; color: #212529; }
        .btn-cancel { background: #6c757d; color: white; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Catatan Diary</h2>
        <form action="" method="POST">
            <label for="title">Judul Catatan</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>

            <label for="content">Isi Cerita</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($row['content']); ?></textarea>

            <div style="text-align: right;">
                <a href="index.php" class="btn btn-cancel">Batal</a>
                <button type="submit" name="update" class="btn btn-submit">Perbarui</button>
            </div>
        </form>
    </div>
</body>
</html>