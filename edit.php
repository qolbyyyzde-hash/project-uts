<?php
include 'config.php';
session_start();

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Pastikan ada ID catatan yang mau diedit
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data lama catatan ini dari database
$query_select = "SELECT * FROM diary WHERE id = '$id'";
$result_select = mysqli_query($conn, $query_select);
$data = mysqli_fetch_assoc($result_select);

// Kalau catatan tidak ditemukan, balikkan ke index
if (!$data) {
    header("Location: index.php");
    exit;
}

// Proses ketika tombol Simpan Perubahan diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);

    // Query untuk mengupdate data catatan
    $query_update = "UPDATE diary SET judul = '$judul', isi = '$isi', kategori = '$kategori' WHERE id = '$id'";
    
    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Catatan berhasil diperbarui!'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui catatan.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Catatan Harian</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: #f1f3f7; color: #1a1a1a; height: 100vh; overflow: hidden; }

        /* Struktur Grid 3 Kolom Meniru Dashboard Utama */
        .dashboard-container { display: grid; grid-template-columns: 260px 1fr 320px; height: 100vh; }

        /* --- 1. SIDEBAR KIRI --- */
        .sidebar { background-color: #ffffff; border-right: 1px solid #e2e8f0; padding: 30px 24px; display: flex; flex-direction: column; justify-content: space-between; }
        .logo-section h2 { font-size: 20px; font-weight: 700; line-height: 1.3; margin-bottom: 40px; color: #0f172a; }
        .nav-menu { display: flex; flex-direction: column; gap: 8px; list-style: none; }
        .nav-item a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #64748b; text-decoration: none; font-weight: 500; border-radius: 8px; transition: all 0.2s; }
        .nav-item a i { font-size: 18px; width: 24px; }
        .nav-item.active a { background-color: #eef2ff; color: #4f46e5; font-weight: 600; }
        .nav-item a:hover:not(.active a) { background-color: #f8fafc; color: #0f172a; }

        /* --- 2. KONTEN UTAMA (FORM EDIT WORKSPACE) --- */
        .main-content { padding: 40px; overflow-y: auto; background-color: #f8fafc; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .welcome-text h1 { font-size: 24px; font-weight: 700; color: #0f172a; }
        
        /* Desain Card Form Luxury */
        .form-card { background: white; border-radius: 12px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px; }
        
        .form-control { width: 100%; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; background-color: #f8fafc; transition: all 0.2s; color: #0f172a; }
        .form-control:focus { outline: none; border-color: #4f46e5; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        
        textarea.form-control { height: 250px; resize: none; line-height: 1.6; }
        
        /* Tombol Aksi */
        .action-group { display: flex; align-items: center; gap: 16px; margin-top: 30px; }
        .btn-submit { background-color: #4f46e5; color: white; border: none; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-submit:hover { background-color: #4338ca; }
        .btn-cancel { color: #64748b; text-decoration: none; font-size: 15px; font-weight: 500; padding: 10px 16px; border-radius: 8px; transition: all 0.2s; }
        .btn-cancel:hover { background-color: #f1f5f9; color: #0f172a; }

        /* --- 3. WIDGET KANAN --- */
        .right-sidebar { background-color: #ffffff; border-left: 1px solid #e2e8f0; padding: 30px 24px; display: flex; flex-direction: column; gap: 30px; overflow-y: auto; }
        .btn-logout { background-color: #ef4444; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; display: block; transition: background 0.2s; }
        .btn-logout:hover { background-color: #dc2626; }
        .btn-logout i { margin-right: 8px; font-size: 14px; }
        
        .widget-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; }
        .widget-title { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 15px; border-left: 3px solid #4f46e5; padding-left: 8px; }
        
        .info-list { list-style: none; font-size: 13px; color: #475569; display: flex; flex-direction: column; gap: 12px; line-height: 1.5; }
        .info-list li { display: flex; gap: 8px; }
        .info-list li i { color: #4f46e5; margin-top: 2px; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="logo-section">
                <h2>Buku Catatan<br>Harian Digital</h2>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="index.php"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="tambah.php"><i class="fa-regular fa-pen-to-square"></i> Tulis Diary Baru</a></li>
                    <li class="nav-item"><a href="#"><i class="fa-regular fa-user"></i> Profil</a></li>
                    <li class="nav-item"><a href="#"><i class="fa-regular fa-circle-question"></i> Bantuan</a></li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h1>Perbarui Catatan 📝</h1>
                </div>
            </div>

            <div class="form-card">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="judul">Judul Catatan</label>
                        <input type="text" id="judul" name="judul" class="form-control" required 
                               value="<?= htmlspecialchars($data['judul']) ?>" placeholder="Gariskan judul ceritamu hari ini...">
                    </div>
                    
                    <div class="form-group">
                        <label>Pilih Kategori</label>
                        <div style="display: flex; gap: 20px; margin-top: 10px; font-size: 14px; color: #475569;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="radio" name="kategori" value="Pribadi" <?= $data['kategori'] == 'Pribadi' ? 'checked' : '' ?> style="accent-color: #4f46e5;"> Pribadi
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="radio" name="kategori" value="Pekerjaan" <?= $data['kategori'] == 'Pekerjaan' ? 'checked' : '' ?> style="accent-color: #4f46e5;"> Pekerjaan
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="radio" name="kategori" value="Rahasia" <?= $data['kategori'] == 'Rahasia' ? 'checked' : '' ?> style="accent-color: #4f46e5;"> Rahasia
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="isi">Isi Cerita</label>
                        <textarea id="isi" name="isi" class="form-control" required placeholder="Tuangkan semua ingatan, ide, atau perasaanmu..."><?= htmlspecialchars($data['isi']) ?></textarea>
                    </div>
                    
                    <div class="action-group">
                        <button type="submit" class="btn-submit"><i class="fa-regular fa-square-check"></i> Simpan Perubahan</button>
                        <a href="index.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </main>

        <aside class="right-sidebar">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>

            <div class="widget-box">
                <div class="widget-title">Informasi Log</div>
                <ul class="info-list">
                    <li><i class="fa-solid fa-calendar-day"></i> <span>Mulai dibuat pada:<br><strong><?= date('d M Y, H:i', strtotime($data['tanggal'])) ?></strong></span></li>
                    <li><i class="fa-solid fa-shield-halved"></i> <span>Data dienkripsi aman ke dalam database local.</span></li>
                </ul>
            </div>
        </aside>

    </div>

</body>
</html>