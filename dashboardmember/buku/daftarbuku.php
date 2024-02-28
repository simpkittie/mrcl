<?php
$conn = mysqli_connect("localhost", "root", "", "eperpus");
session_start();

if(!isset($_SESSION["user_id"]) ) {
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
function isBookSaved($userId, $bookId, $connection) {
    $query = "SELECT * FROM koleksi WHERE userid = $userId AND bukuid = $bookId";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result) > 0;
}

if(isset($_POST['simpan'])) {
    $userId = $_SESSION["user_id"];
    $bookId = $_POST['bukuid'];
    $isSaved = isBookSaved($userId, $bookId, $conn);
    
    if($isSaved) {
        $deleteQuery = "DELETE FROM koleksi WHERE userid = $userId AND bukuid = $bookId";
        mysqli_query($conn, $deleteQuery);
    } else {
        $insertQuery = "INSERT INTO koleksi (userid, bukuid) VALUES ($userId, $bookId)";
        mysqli_query($conn, $insertQuery);
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>User Dashboard</title>
    <style>
        body {
            background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            margin-top: 70px;
            height: 100vh;
        }

        .navbar-brand {
            position: absolute;
            top: 0;
            left: 0;
            margin: 10px;
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

        .dropdown {
            position: absolute;
            top: 25px;
            right: 0;
        }

        .dropdown-menu {
            margin-top: 10px;
        }

        .card{
            border: 2px solid #000;
        }
        .btn-tandai {
            background-color: #3c4a6b;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left:10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-right: 0px;
        }

        .btn-tandai:hover {
            background-color: #2c3756;
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

        @media screen and (max-width: 600px) {
            .d-flex flex-wrap gap-2 cardImg a img {
                width: 200px;
            }
        }
    </style>
</head>
<body>
<h2 class="title">Daftar Buku</h2>
    <a class="navbar-brand">
        <img src="../../assets/logo2.png" alt="logo" width="100px">
    </a>
    
    <ul class="position-absolute top-0 end-0 mt-2 p-2">
    <a href="../dashboardmember.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    </ul>
     <div class="p-4 mt-5">      
       <form action="" method="post" class="mt-5">
       <div class="input-group d-flex justify-content-end mb-3">
         <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="cari judul atau kategori buku...">
         <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
       </div>
      </form>
      
      <!--Card buku-->
    <div class="layout-card-custom">
    <?php foreach ($buku as $item) : ?>
                <?php
                $isSaved = isBookSaved($_SESSION["user_id"], $item["id"], $conn);
                $buttonColor = $isSaved ? 'pink' : '#3c4a6b';
                $buttonLabel = $isSaved ? '<i class="fas fa-check"></i> Disimpan' : '<i class="far fa-bookmark"></i> Simpan';
                ?>
       <div class="card" style="width: 15rem;">
         <img src="../../imgDB/<?= $item["cover"]; ?>" class="card-img-top" alt="coverBuku" height="250px">
         <div class="card-body">
           <h5 class="card-title"><?= $item["judul"]; ?></h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Kategori : <?= $item["nama_kategori"]; ?></li>            
          </ul>
          <div class="card-body text-center">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="userid" value="<?php echo $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="bukuid" value="<?= $item["id"]; ?>">
                        <button type="submit" name="simpan" class="btn btn-tandai" data-bukuid="<?= $item["id"]; ?>" style="background-color: <?= $buttonColor; ?>">
                            <?= $buttonLabel; ?>
                        </button>
                        <a class="btn btn-primary btn-ulasan" href="ulasan.php?id=<?= $item["id"]; ?>">Ulasan</a>
                        <a class="btn btn-success" href="detailBuku.php?id=<?= $item["id"]; ?>">Pinjam</a>
                    </form>
                </div>
        </div>
       <?php endforeach; ?>
      <div>
     </div>
    </body>
    </html>
