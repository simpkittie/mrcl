<?php
require_once('../../backend/config.php');

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit;
}

$query = "SELECT * FROM peminjaman WHERE status = 'sudah dikembalikan'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous" rel="stylesheet">
    <style>
        body {
            background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            height: 100vh;
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
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="../dashboardpetugas.php" class="btn btn-primary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
    <h2 style="text-align: center;">Daftar Pengembalian</h2>
    <div class="row">
        <?php while ($row_pinjaman = mysqli_fetch_assoc($result)) { ?>
            <?php
            $user_id = $row_pinjaman['userid'];
            $query_user = "SELECT * FROM user WHERE id = $user_id";
            $result_user = mysqli_query($conn, $query_user);
            $user = mysqli_fetch_assoc($result_user);

            $buku_id = $row_pinjaman['bukuid'];
            $query_buku = "SELECT * FROM buku WHERE id = $buku_id";
            $result_buku = mysqli_query($conn, $query_buku);
            $buku = mysqli_fetch_assoc($result_buku);
            ?>
            <div class="col-md-4">
                <div class="img-container">
                    <img src="../../imgDB/<?= $buku["cover"]; ?>" alt="Gambar Buku">
                </div>
                <h5><?= $buku['judul']; ?></h5>
                <p><strong>ID User:</strong> <?= $user['id']; ?></p>
                <p><strong>Username User:</strong> <?= $user['username']; ?></p>
                <p><strong>Tanggal Peminjaman:</strong> <?= $row_pinjaman['tanggal_peminjaman']; ?></p>
                <p><strong>Tanggal Pengembalian:</strong> <?= $row_pinjaman['tanggal_pengembalian']; ?></p>
                <p><strong>Status:</strong> <?= $row_pinjaman['status']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
