<?php
// Mengaktifkan laporan eror agar kalau ada masalah langsung kelihatan
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$pass = "root"; // MAMP di Mac bawaannya menggunakan password "root"
$db   = "diary_db";
$port = 8889;    // Port default MySQL untuk MAMP di Mac

// Membuat koneksi ke database
$conn = mysqli_connect($host, $user, $pass, $db, $port);

// Cek apakah koneksi berhasil atau gagal
if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
// JANGAN tulis tag ?>