<?php
require_once('config.php');

if(isset($_GET['pinjaman_id']) && isset($_GET['book_id'])) {
    $pinjaman_id = $_GET['pinjaman_id'];
    $book_id = $_GET['book_id'];
    $update_query = "UPDATE peminjaman SET status = 'sudah dikembalikan' WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $pinjaman_id);
    
    if ($stmt->execute()) {
        header("Location: ../dashboardmember/buku/rating.php?id=$book_id");
        exit;
    } else {
        echo "Gagal memperbarui status peminjaman.";
    }    
} 
?>
