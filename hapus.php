<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ganti kata 'diary' dengan nama tabel aslimu
    $query = "DELETE FROM diary WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Catatan berhasil dihapus!'); window.location.href='index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location.href='index.php';</script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;}