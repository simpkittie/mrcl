<?php
// Mengambil koneksi ke database (harus disesuaikan dengan konfigurasi Anda)
require_once('config.php');

// Mengecek apakah data yang dibutuhkan telah diterima dari formulir
if(isset($_POST['rating'], $_POST['review'], $_POST['pinjaman_id'], $_POST['book_id'])) {
    // Mengambil data dari formulir
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $pinjaman_id = $_POST['pinjaman_id'];
    $book_id = $_POST['book_id'];

    // Menyiapkan query untuk memeriksa apakah pengguna telah memberikan ulasan atau rating sebelumnya pada buku yang sama
    $query_check_existing_review = "SELECT COUNT(*) AS total FROM ulasan WHERE userid = ? AND bukuid = ?";
    $stmt_check_existing_review = $conn->prepare($query_check_existing_review);
    $stmt_check_existing_review->bind_param("ii", $userid, $book_id);
    $stmt_check_existing_review->execute();
    $result_check_existing_review = $stmt_check_existing_review->get_result();
    $row_check_existing_review = $result_check_existing_review->fetch_assoc();
    $total_reviews = $row_check_existing_review['total'];

    // Jika pengguna telah memberikan ulasan atau rating sebelumnya pada buku yang sama, kembalikan pesan kesalahan
    if ($total_reviews > 0) {
        echo "Anda telah memberikan ulasan atau rating untuk buku ini sebelumnya.";
        exit();
    }

    // Jika belum ada ulasan atau rating dari pengguna untuk buku yang sama, lanjutkan dengan memasukkan ulasan baru ke dalam database
    // Menyiapkan query untuk memasukkan ulasan ke dalam database
    $query = "INSERT INTO ulasan (userid, bukuid, ulasan, rating) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    // Mengeksekusi query dengan mengikat nilai parameter
    $stmt->bind_param("iisi", $userid, $bukuid, $review, $rating);
    
    // Mendapatkan user id dari sesi (harus disesuaikan dengan aplikasi Anda)
    session_start();
    $userid = $_SESSION["user_id"];

    // Mengikat nilai bukuid dengan nilai yang diterima dari formulir
    $bukuid = $book_id;

    // Mengeksekusi query untuk memasukkan ulasan baru ke dalam database
    $stmt->execute();

    // Mengupdate status peminjaman buku menjadi 'sudah dikembalikan' (harus disesuaikan dengan aplikasi Anda)
    $query_update_status = "UPDATE peminjaman SET status = 'sudah dikembalikan' WHERE id = ?";
    $stmt_update_status = $conn->prepare($query_update_status);
    $stmt_update_status->bind_param("i", $pinjaman_id);
    $stmt_update_status->execute();

    // Mengarahkan kembali pengguna ke halaman daftar buku yang dipinjam setelah memberikan ulasan
    header("Location: ../dashboardmember/buku/detailpeminjaman.php");
    exit();
} else {
    // Jika data tidak lengkap, mengarahkan kembali pengguna ke halaman sebelumnya atau menampilkan pesan kesalahan
    echo "Data tidak lengkap";
}
?>
