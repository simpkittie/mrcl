<?php
$conn = mysqli_connect("localhost", "root", "", "eperpus");
session_start();

if(!isset($_SESSION["user_id"])) {
  header("Location: ../../login.php");
  exit;
}

if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    $query = "SELECT buku.*, kategoribuku.nama_kategori FROM buku
              JOIN kategoribuku_relasi ON buku.id = kategoribuku_relasi.buku_id
              JOIN kategoribuku ON kategoribuku_relasi.kategori_id = kategoribuku.id
              WHERE buku.judul LIKE '%$keyword%'
              OR kategoribuku.nama_kategori LIKE '%$keyword%'";
  } else {
    $query = "SELECT buku.*, kategoribuku.nama_kategori FROM buku
              JOIN kategoribuku_relasi ON buku.id = kategoribuku_relasi.buku_id
              JOIN kategoribuku ON kategoribuku_relasi.kategori_id = kategoribuku.id";
  }

$result = mysqli_query($conn, $query);
$buku = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola buku || Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous" rel="stylesheet">
    <style>
        body {
            background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            height: auto;
        }

        .title {
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
            text-shadow: 0 0 3px black;
        }

        .layout-card-custom {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .back-btn {
            text-align:center;
            margin-bottom: 20px;            
            margin-left: -40px;
            position: absolute;
            top: 0;
            transform: translate(-50%, 50%);
            background-color: #3c4a6b;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #2c3859;
        }

        .card{
            border: 2px solid #000;
        }

    </style>
</head>
<body>
<a class="navbar-brand">
        <img src="../../assets/logo2.png" alt="logo" width="100px">
    </a>
    <ul class="position-absolute top-0 end-0 mt-2 p-2">
    <a href="../dashboardadmin.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </ul>
        <div class="p-4 mt-4">
            <h2 class="title">Daftar Buku</h2>
            
            <form action="" method="post" class="mt-3">
                <div class="input-group d-flex justify-content-end mb-3">
                    <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="cari data buku...">
                    <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            
            <div class="layout-card-custom">
                <?php foreach ($buku as $item) : ?>
                    <div class="card" style="width: 15rem;">
                        <img src="../../imgDB/<?= $item["cover"]; ?>" class="card-img-top" alt="coverBuku" height="250px">
                        <div class="card-body">
                            <h5 class="card-title"><?= $item["judul"]; ?></h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Kategori : <?= $item["nama_kategori"]; ?></li>
                            <li class="list-group-item">Id Buku : <?= $item["id"]; ?></li>                    
                        </ul>
                        <div class="card-body">
                            <a class="btn btn-success" href="updatebuku.php?id=<?= $item["id"]; ?>" id="review">Edit</a>
                            <a name ="delete" class="btn btn-danger" href="../../backend/deletebuku.php?id=<?= $item["id"]; ?>" onclick="return confirm('Yakin ingin menghapus data buku ? ');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
