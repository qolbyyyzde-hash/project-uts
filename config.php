<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "diary_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}