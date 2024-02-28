<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eperpus";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if(isset($_GET['id'])) {
    $id_buku = $_GET['id'];
    $sql_relasi = "DELETE FROM kategoribuku_relasi WHERE buku_id = ?";
    $stmt_relasi = $conn->prepare($sql_relasi);
    $stmt_relasi->bind_param("i", $id_buku);
    $stmt_relasi->execute();
    $sql_buku = "DELETE FROM buku WHERE id = ?";
    $stmt_buku = $conn->prepare($sql_buku);
    $stmt_buku->bind_param("i", $id_buku);
    $stmt_buku->execute();
    $stmt_relasi->close();
    $stmt_buku->close();

    echo "<p>Buku berhasil dihapus!</p>";
    header("Location: ../dashboardadmin/buku/daftarbuku.php");
    exit();
} else {
    echo "ID buku tidak ditemukan dalam permintaan.";
}

$conn->close();
?>
