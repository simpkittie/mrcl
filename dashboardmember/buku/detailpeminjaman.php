<?php
require_once('../../backend/config.php');

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'], $_POST['ulasan'])) {
    $pinjaman_id = $_POST['pinjaman_id'];
    $rating = $_POST['rating'];
    $ulasan = $_POST['ulasan'];
    
    // Simpan rating dan ulasan
    $query_update = "UPDATE peminjaman SET rating = ?, ulasan = ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("isi", $rating, $ulasan, $pinjaman_id);
    $stmt_update->execute();
    
    // Redirect untuk refresh halaman
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku yang Sedang Dipinjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            height: auto;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .img-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .img-container img {
            max-width: 200px;
            height: auto;
        }
        .btn-back {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="btn-back">
        <a href="../dashboardmember.php" class="btn btn-primary">Kembali</a>
    </div>
    <h2 style="text-align: center;">Daftar Buku yang Sedang Dipinjam</h2>
    <div class="row">
        <?php 
        $user_id = $_SESSION["user_id"];
        $query_pinjaman = "SELECT p.*, b.* FROM peminjaman p INNER JOIN buku b ON p.bukuid = b.id WHERE p.userid = ? AND p.status = 'belum dikembalikan'";
        $stmt_pinjaman = $conn->prepare($query_pinjaman);
        $stmt_pinjaman->bind_param("i", $user_id);
        $stmt_pinjaman->execute();
        $result_pinjaman = $stmt_pinjaman->get_result();
        while ($row_pinjaman = $result_pinjaman->fetch_assoc()) { 
            $buku_id = $row_pinjaman['bukuid'];
        ?>
        <div class="col-md-4">
            <div class="img-container">
                <img src="../../imgDB/<?= $row_pinjaman["cover"]; ?>" alt="Gambar Buku">
            </div>
            <h5><?= $row_pinjaman['judul']; ?></h5>
            <p><strong>Penulis:</strong> <?= $row_pinjaman['penulis']; ?></p>
            <p><strong>Penerbit:</strong> <?= $row_pinjaman['penerbit']; ?></p>
            <p><strong>Tanggal Peminjaman:</strong> <?= $row_pinjaman['tanggal_peminjaman']; ?></p>
            <p><strong>Tanggal Pengembalian:</strong> <?= $row_pinjaman['tanggal_pengembalian']; ?></p>
            <p><strong>Status:</strong> <?= $row_pinjaman['status']; ?></p>
            <?php if (empty($row_pinjaman['rating']) && empty($row_pinjaman['ulasan'])): ?>
            <form class="rating-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" name="pinjaman_id" value="<?= $row_pinjaman['id']; ?>">
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating (1-5)</label>
                    <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                </div>
                <div class="mb-3">
                    <label for="review" class="form-label">Ulasan</label>
                    <textarea class="form-control" id="review" name="ulasan"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Berikan Ulasan</button>
            </form>
            <?php else: ?>
            <div class="mb-3">
                <p><strong>Rating:</strong> <?= isset($row_pinjaman['rating']) ? $row_pinjaman['rating'] : 'Belum ada rating'; ?></p>
                <p><strong>Ulasan:</strong> <?= isset($row_pinjaman['ulasan']) ? $row_pinjaman['ulasan'] : 'Belum ada ulasan'; ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php 
        } // end while
        ?>
    </div>
</div>
</body>
</html>
