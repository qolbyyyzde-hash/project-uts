<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$note_id = mysqli_real_escape_string($conn, $_GET['id']);

// Eksekusi penghapusan data dengan memastikan kepemilikan user_id agar aman
$query = "DELETE FROM notes WHERE id = '$note_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus catatan: " . mysqli_error($conn);
}
?>