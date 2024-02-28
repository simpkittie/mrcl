<?php
require('config.php');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $bukuid = $_POST['bukuid'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $status = "belum dikembalikan";

    $sql_check = "SELECT * FROM peminjaman WHERE userid = ? AND bukuid = ? AND status = 'belum dikembalikan'";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $userid, $bukuid);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Maaf, Anda sudah meminjam buku ini dan belum mengembalikannya.'); window.location.href='../dashboardmember/buku/pinjambuku.php';</script>";
        exit();
    }

    $sql_insert = "INSERT INTO peminjaman (userid, bukuid, tanggal_peminjaman, tanggal_pengembalian, status) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt_insert = $conn->prepare($sql_insert);

    $stmt_insert->bind_param("iisss", $userid, $bukuid, $tanggal_peminjaman, $tanggal_pengembalian, $status);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Peminjaman Berhasil'); window.location.href='../dashboardmember/buku/detailpeminjaman.php';</script>";
        exit();
    } else {
        echo "<script>alert('Peminjaman Gagal'); window.location.href='../dashboardmember/buku/pinjambuku.php';</script>";
        exit();
    }
}

$conn->close();
?>
