<?php
require 'config.php';
session_start();

// Proteksi halaman: jika belum login, tendang ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Ambil catatan milik user yang sedang aktif saja
$query = "SELECT * FROM notes WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Diary Saya</title>
    <style>
        /* --- Tambahkan kode baru ini di paling atas dalam <style> --- */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-color: #f4f6f9;
        }
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            padding: 20px;
            border-right: 1px solid #e0e0e0;
        }
        .sidebar h2 { color: #5c67f2; margin-bottom: 30px; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li { margin-bottom: 15px; }
        .sidebar ul li a { text-decoration: none; color: #333; font-weight: bold; display: block; padding: 10px; border-radius: 4px; }
        .sidebar ul li a.active, .sidebar ul li a:hover { background-color: #f0f2ff; color: #5c67f2; }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        /* --- Akhir kode baru --- */

        /* Kode lama kamu yang di screenshot biarkan tetap ada di bawahnya */
        .note-card { background: ... }
        .note-meta { color: #888; ... }
        .note-actions { margin-top: 15px; }
        .empty-state { text-align: center; ... }
        .container { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn { padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-add { background: #28a745; color: white; }
        .btn-logout { background: #dc3545; color: white; }
        .btn-edit { background: #ffc107; color: #212529; }
        .btn-delete { background: #dc3545; color: white; }
        .note-card { background: white; padding: 20px; border-radius: 8px; margin-top: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: relative; }
        .note-meta { color: #888; font-size: 12px; margin-bottom: 10px; }
        .note-actions { margin-top: 15px; }
        .empty-state { text-align: center; color: #666; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="wrapper">
    <div class="sidebar">
        <h2>LeJournal</h2>
        <ul>
            <li><a href="index.php" class="active">Dashboard</a></li>
            <li><a href="tambah.php">+ Tulis Diary</a></li>
            <li><a href="logout.php" onclick="return confirm('Yakin ingin keluar?')">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
    <div class="container">
        <div class="header">
            <h2>Halo, <?php echo htmlspecialchars($username); ?>! 👋</h2>
            <div>
                <a href="tambah.php" class="btn btn-add">+ Tulis Diary</a>
                <a href="logout.php" class="btn btn-logout" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>

        <h3>Daftar Catatan Harian Anda</h3>

        <?php if (mysqli_num_rows($result) === 0) : ?>
            <div class="empty-state">
                <p>Belum ada catatan harian. Mulai menulis cerita pertamamu hari ini!</p>
            </div>
        <?php else : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="note-card">
                    <div class="note-meta">Ditulis pada: <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></div>
                    <h2 style="margin: 0 0 10px 0; color: #333;"><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p style="color: #555; line-height: 1.6; white-space: pre-line;"><?php echo htmlspecialchars($row['content']); ?></p>
                    
                    <div class="note-actions">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan ini?')">Hapus</a>
                    </div>
                </div>
            <?php endwhile; ?>
       <?php endif; ?>
            </div> </div> </div> </body>
</html>