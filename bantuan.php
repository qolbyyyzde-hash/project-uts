<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bantuan - Buku Catatan Harian Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: 240px 1fr 280px;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            padding: 24px;
        }

        .logo-section h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 30px;
            line-height: 1.3;
        }

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
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
        }

        .nav-item.active a {
            background-color: #ede9fe;
            color: #4f46e5;
            font-weight: 600;
        }

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
    </style>
</head>

<body>
    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="logo-section">
                <h2>Buku Catatan<br>Harian Digital</h2>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="index.php"><i class="fa-solid fa-border-all"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="tambah.php"><i class="fa-regular fa-pen-to-square"></i> Tulis Diary
                            Baru</a></li>
                    <li class="nav-item"><a href="profil.php"><i class="fa-regular fa-user"></i> Profil</a></li>
                    <li class="nav-item active"><a href="bantuan.php"><i class="fa-regular fa-circle-question"></i> Bantuan</a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-bar">
                <div class="welcome-text">
                    <h1>Pusat Bantuan ❓</h1>
                </div>
                <a href="tambah.php" class="btn-add">+ Tulis Diary</a>
            </div>

            <div class="feed-container" style="display: flex; flex-direction: column; gap: 8px;">
                <div
                    style="background: white; border-radius: 8px; padding: 12px 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                    <h4 style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 700;"> Bagaimana cara
                        menambah catatan baru?</h4>
                    <p style="font-size: 12.5px; color: #475569; line-height: 1.4; margin: 0;">Kamu bisa menekan tombol
                        menu <strong>"Tulis Diary Baru"</strong> di sidebar kiri atau tombol hijau <strong>"+ Tulis
                            Diary"</strong> di bagian atas dashboard.</p>
                </div>

                <div
                    style="background: white; border-radius: 8px; padding: 12px 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                    <h4 style="font-size: 14px; color: #0f172a; margin: 0 0 4px 0; font-weight: 700;"> Apakah catatan
                        saya aman?</h4>
                    <p style="font-size: 12.5px; color: #475569; line-height: 1.4; margin: 0;">Ya, seluruh catatan
                        harian Anda bersifat privat dan hanya bisa dibaca oleh akun yang berhasil melakukan login ke
                        sistem jurnal ini.</p>
                </div>
            </div>
        </main>

        <aside class="right-sidebar">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </aside>

    </div>
</body>

</html>