<?php
session_start();
require_once('config.php');

if(isset($_GET['pinjaman_id']) && isset($_GET['book_id']) && isset($_GET['rating']) && isset($_GET['ulasan'])) {
    $pinjaman_id = $_GET['pinjaman_id'];
    $book_id = $_GET['book_id'];
    $rating = $_GET['rating'];
    $ulasan = $_GET['ulasan'];

    $check_query = "SELECT id FROM ulasan WHERE userid = ? AND bukuid = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("ii", $_SESSION['user_id'], $book_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $update_query = "UPDATE ulasan SET ulasan = ?, rating = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("sii", $ulasan, $rating, $row['id']);
        
        if ($stmt_update->execute()) {
            $update_peminjaman_query = "UPDATE peminjaman SET status = 'sudah dikembalikan' WHERE id = ?";
            $stmt_update_peminjaman = $conn->prepare($update_peminjaman_query);
            $stmt_update_peminjaman->bind_param("i", $pinjaman_id);
            
            if ($stmt_update_peminjaman->execute()) {
                header("Location: ../dashboardmember/buku/detailpeminjaman.php");
                exit;
            } else {
                echo "Gagal memperbarui status peminjaman.";
            }
        } else {
            echo "Gagal memperbarui ulasan.";
        }
    } else {
        $insert_query = "INSERT INTO ulasan (userid, bukuid, ulasan, rating) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iisi", $_SESSION['user_id'], $book_id, $ulasan, $rating);    
    
        if ($stmt->execute()) {
            $update_peminjaman_query = "UPDATE peminjaman SET status = 'sudah dikembalikan' WHERE id = ?";
            $stmt_update_peminjaman = $conn->prepare($update_peminjaman_query);
            $stmt_update_peminjaman->bind_param("i", $pinjaman_id);
            
            if ($stmt_update_peminjaman->execute()) {
                header("Location: ../dashboardmember/buku/detailpeminjaman.php");
                exit;
            } else {
                echo "Gagal memperbarui status peminjaman.";
            }
        } else {
            echo "Gagal memasukkan ulasan dan rating.";
        }
    }
} 
?>
