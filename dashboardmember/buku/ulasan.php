<?php
require_once('../../backend/config.php');

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: daftarbuku.php");
    exit;
}
$id_buku = $_GET['id'];

$query_buku = "SELECT * FROM buku WHERE id = ?";
$stmt_buku = $conn->prepare($query_buku);
$stmt_buku->bind_param("i", $id_buku);
$stmt_buku->execute();
$result_buku = $stmt_buku->get_result();
$buku = $result_buku->fetch_assoc();

$query_avg_rating = "SELECT AVG(rating) AS avg_rating FROM ulasan WHERE bukuid = ?";
$stmt_avg_rating = $conn->prepare($query_avg_rating);
$stmt_avg_rating->bind_param("i", $id_buku);
$stmt_avg_rating->execute();
$result_avg_rating = $stmt_avg_rating->get_result();
$row_avg_rating = $result_avg_rating->fetch_assoc();
$avg_rating = $row_avg_rating['avg_rating'];

$query_ulasan = "SELECT * FROM ulasan WHERE bukuid = ?";
$stmt_ulasan = $conn->prepare($query_ulasan);
$stmt_ulasan->bind_param("i", $id_buku);
$stmt_ulasan->execute();
$result_ulasan = $stmt_ulasan->get_result();
$ulasan = $result_ulasan->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulasan Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
        }
        .img-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .img-container img {
            max-width: 200px;
            height: auto;
        }
        .ulasan-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
        }
        .card {
            background-color: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .card-body {
            padding: 10px;
        }
        .rating {
            color: #000;
            font-size: 24px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Daftar Ulasan Buku</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="img-container">
                <img src="../../imgDB/<?= $buku["cover"]; ?>" alt="Cover Buku">
                <div class="rating">
                    <?php
                    if ($avg_rating !== null) {
                        $rating_out_of_5 = ($avg_rating / 5) * 5;
                        echo "Rating: " . number_format($rating_out_of_5, 1) . "/5.0";
                    } else {
                        echo 'Belum ada ulasan';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ulasan-container">
                <?php foreach ($ulasan as $review) : ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ulasan oleh User ID: <?= $review['userid']; ?></h5>
                            <p class="card-text">Rating: <?= $review['rating']; ?></p>
                            <p class="card-text">Ulasan: <?= $review['ulasan']; ?></p>
                        </div>
                    </div>                    
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-3 text-end">                
            <a href="daftarbuku.php" class="btn btn-danger me-2">Kembali</a>
        </div>
    </div>
</div>
</body>
</html>
