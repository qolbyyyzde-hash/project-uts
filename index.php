<?php
include 'config.php';
session_start();

// Proteksi halaman: Kalau belum login, balikkan ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil username yang sedang aktif di session
$username_login = $_SESSION['username'];

// Ambil filter kategori yang aktif
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';

if ($filter_kategori !== 'Semua') {
    $query = "SELECT * FROM diary WHERE kategori = '$filter_kategori' AND username = '$username_login' ORDER BY tanggal DESC";
} else {
    $query = "SELECT * FROM diary WHERE username = '$username_login' ORDER BY tanggal DESC";
}

// PASTIKAN BARIS INI ADA DI ATAS SEBELUM TAG TERTUTUP:
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Catatan Harian Digital</title>
    <!-- Tambahkan CDN Font Awesome untuk ikon premium -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-logout i {
            margin-right: 8px;
            font-size: 14px;
        }

        .nav-item a i {
            font-size: 18px;
            width: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f1f3f7;
            color: #1a1a1a;
            height: 100vh;
            overflow: hidden;
        }

        .dashboard-container {
    display: grid;
    grid-template-columns: 240px 1fr 280px; /* Samakan angka ini di semua file */
    min-height: 100vh;}

        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 24px;
        }

        .logo-section h2 { font-size: 20px; font-weight: 700; line-height: 1.3; margin-bottom: 40px; color: #0f172a; }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-item a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    color: #64748b;
    text-decoration: none;
    font-size: 14px; /* Gunakan 14px agar sekecil dan serapi profil */
    font-weight: 500;
    border-radius: 8px;
}

        .nav-item.active a {
            background-color: #ede9fe;
            color: #4f46e5;
            font-weight: 600;
        }

        /* --- 2. KONTEN TENGAH (MAIN FEED) --- */
        .main-content {
            padding: 40px;
            background-color: #f8fafc;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top-bar h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .btn-add {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .right-sidebar {
            background-color: #ffffff;
            border-left: 1px solid #e2e8f0;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .btn-logout {
            background-color: #ef4444;
            color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        /* Card Catatan */
        .diary-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s;
        }

        .diary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .meta-date {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .diary-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .diary-card p {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-edit {
            background-color: #f59e0b;
            color: white;
        }

        .btn-edit:hover {
            background-color: #d97706;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        /* --- 3. WIDGET KANAN --- */
        .right-sidebar {
            background-color: #ffffff;
            border-left: 1px solid #e2e8f0;
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            gap: 30px;
            overflow-y: auto;
        }

        .btn-logout {
            background-color: #ef4444;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            display: block;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background-color: #dc2626;
        }

        /* Widget Box */
        .widget-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
        }

        .widget-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
            border-left: 3px solid #4f46e5;
            padding-left: 8px;
        }

        /* Dummy Kalender Mini */
        .mini-calendar {
            font-size: 12px;
            text-align: center;
        }

        .cal-header {
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 10px;
        }

        .cal-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 5px;
        }

        .cal-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            color: #334155;
        }

        .cal-dates span {
            padding: 4px;
            display: inline-block;
        }

        .cal-dates .active-date {
            background: #4f46e5;
            color: white;
            border-radius: 4px;
            font-weight: bold;
        }

        /* Radio Kategori */
        .category-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 14px;
        }

        .cat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
        }

        .cat-item input {
            accent-color: #4f46e5;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">

        <!-- ================= 1. SIDEBAR KIRI ================= -->
       <aside class="sidebar">
    <div class="logo-section">
        <h2>Buku Catatan<br>Harian Digital</h2>
        <ul class="nav-menu">
            <li class="nav-item active"><a href="index.php"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
            <li class="nav-item"><a href="tambah.php"><i class="fa-regular fa-pen-to-square"></i> Tulis Diary Baru</a></li>
            <li class="nav-item"><a href="profil.php"><i class="fa-regular fa-user"></i> Profil</a></li>
            <li class="nav-item"><a href="bantuan.php"><i class="fa-regular fa-circle-question"></i> Bantuan</a></li>
        </ul>
    </div>
</aside>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <!-- Membungkus text salam dengan tag h2 agar kembali besar dan tebal -->
                    <h2 style="font-size: 24px; font-weight: bold; color: #0f172a; margin-bottom: 8px;">
                        Halo, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Pengguna'); ?>! 👋
                    </h2>
                </div>
                <a href="tambah.php" class="btn-add"><span>+</span> Tulis Diary</a>
            </div>
            <h3 style="margin-bottom: 20px;">Daftar Catatan Harian Anda</h3>

            <div class="feed-container" style="display: flex; flex-direction: column; gap: 8px;">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $format_tanggal = date('d M Y, H:i', strtotime($row['tanggal']));
                        ?>

                        <div class="diary-card"
                            style="background: white; border-radius: 8px; padding: 10px 14px; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.01); width: 100%; box-sizing: border-box;">
                            <div class="card-header"
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                <span class="meta-date" style="font-size: 10.5px; color: #64748b;">
                                    <i class="fa-regular fa-calendar"></i> <?= $format_tanggal ?>
                                </span>

                                <span
                                    style="font-size: 10px; background: #eef2ff; color: #4f46e5; font-weight: 600; padding: 1px 5px; border-radius: 4px; border: 1px solid #e0e7ff;">
                                    <?= htmlspecialchars($row['kategori']) ?>
                                </span>
                            </div>

                            <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 2px 0;">
                                <?= htmlspecialchars($row['judul']) ?></h3>
                            <p style="font-size: 13px; color: #475569; line-height: 1.4; margin: 0;">
                                <?= nl2br(htmlspecialchars($row['isi'])) ?></p>

                            <div class="card-actions"
                                style="display: flex; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 5px;">
                                <a href="edit.php?id=<?= $row['id'] ?>"
                                    style="font-size: 11px; color: #4f46e5; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                                    <i class="fa-regular fa-pen-to-square"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus catatan ini?')"
                                    style="font-size: 11px; color: #ef4444; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </a>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    ?>
                    <div class="empty-state"
                        style="background: white; border-radius: 12px; padding: 40px; text-align: center; border: 1px solid #e2e8f0;">
                        <p style="color: #64748b; font-size: 14px;">Belum ada catatan di kategori ini. Yuk buat catatan
                            pertamamu!</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </main>
        <aside class="right-sidebar">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            <div class="widget-box">
                <div class="widget-title">Kalender</div>
                <div class="mini-calendar">
                    <div class="cal-header">Juni 2026</div>
                    <div class="cal-days">
                        <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                    </div>
                    <div class="cal-dates">
                        <span
                            class="active-date">1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span>
                        <span>8</span><span>9</span><span>10</span><span>11</span><span>12</span><span>13</span><span>14</span>
                        <span>15</span><span>16</span><span>17</span><span>18</span><span>19</span><span>20</span><span>21</span>
                        <span>22</span><span>23</span><span>24</span><span>25</span><span>26</span><span>27</span><span>28</span>
                    </div>
                </div>
            </div>

            <div class="widget-box">
                <div class="widget-title">Kategori</div>
                <div class="category-list">
                    <label class="cat-item">
                        <input type="radio" name="cat" onclick="window.location.href='index.php?kategori=Semua'"
                            <?= $filter_kategori == 'Semua' ? 'checked' : '' ?>> Semua
                    </label>
                    <label class="cat-item">
                        <input type="radio" name="cat" onclick="window.location.href='index.php?kategori=Pribadi'"
                            <?= $filter_kategori == 'Pribadi' ? 'checked' : '' ?>> Pribadi
                    </label>
                    <label class="cat-item">
                        <input type="radio" name="cat" onclick="window.location.href='index.php?kategori=Pekerjaan'"
                            <?= $filter_kategori == 'Pekerjaan' ? 'checked' : '' ?>> Pekerjaan
                    </label>
                    <label class="cat-item">
                        <input type="radio" name="cat" onclick="window.location.href='index.php?kategori=Rahasia'"
                            <?= $filter_kategori == 'Rahasia' ? 'checked' : '' ?>> Rahasia
                    </label>
                </div>
            </div>
        </aside>

    </div>

</body>

</html>