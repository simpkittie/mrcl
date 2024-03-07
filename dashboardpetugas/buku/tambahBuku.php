<?php 
$conn = mysqli_connect("localhost", "root", "", "eperpus"); 
session_start();

if(!isset($_SESSION["user_id"]) ) {
  header("Location: ../../login.php");
  exit;
}

function queryReadData($dataKategori) {
  global $conn;
  $result = mysqli_query($conn, $dataKategori);
  $items = [];
  while($item = mysqli_fetch_assoc($result)) {
    $items[] = $item;
  }     
  return $items;
}

$kategori = queryReadData("SELECT * FROM kategoribuku");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../styles.css">
    <title>Tambah Buku || Petugas</title>
</head>
<body>
    <a class="navbar-brand">
        <img src="../../assets/logo2.png" alt="logo" width="100px">
    </a>

    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../../assets/logoadmin.png" alt="memberLogo" width="40px">
        </button>
        <ul class="dropdown-menu position-absolute mt-2 p-2">
            <li>
                <a class="dropdown-item text-center" href="#">
                    <img src="../../assets/logoadmin.png" alt="adminLogo" width="30px">
                </a>
            </li>
            <li>
                <?php if(isset($_SESSION['user']['username'])): ?>
                    <a class="dropdown-item text-center text-secondary" href="#"> <span class="text-capitalize"><?php echo $_SESSION['user']['username']; ?></a>
                <?php endif; ?>
            </li>
            <hr>         
            <li>
                <a class="dropdown-item text-center p-2 bg-danger text-light rounded" href="../signout.php">Sign Out <i class="fa-solid fa-right-to-bracket"></i></a>
            </li>
        </ul>
    </div>

    <div class="container p-3 mt-5">
        <div class="card p-2 mt-5">
            <div class="row">
                <div class="col-md-6">
                    <a href="kategori.php" class="btn btn-primary btn-lg btn-cyan">Kategori</a>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <a href="../dashboardpetugas.php" class="btn btn-secondary btn-lg btn-kembali">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <h1 class="text-center fw-bold p-3">Form Tambah Buku</h1>
            <form action="../../backend/tambahbukupetugas.php" method="post" enctype="multipart/form-data" class="mt-3 p-2">
                <div class="custom-css-form"> 
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Cover Buku</label>
                        <input class="form-control" type="file" name="gambar" id="formFileMultiple" required>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01">Kategori</label>
                    <select class="form-select" id="inputGroupSelect01" name="kategori">
                        <option selected>Choose</option>
                        <?php foreach ($kategori as $item) : ?>
                            <option><?= $item["nama_kategori"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-book"></i></span>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Buku" aria-label="Judul Buku" aria-describedby="basic-addon1" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Penulis</label>
                    <input type="text" class="form-control" name="penulis" id="exampleFormControlInput1" placeholder="Nama Penulis"  required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" name="penerbit" id="exampleFormControlInput1" placeholder="Nama Penerbit"  required>
                </div>
                <div class="mb-3">
                    <label for="validationCustom01" class="form-label">Tahun Terbit</label>
                    <input type="date" class="form-control" name="tahun_terbit" id="validationCustom01" required>
                </div>
                <div class="form-floating mt-3 mb-3">
                    <textarea class="form-control" placeholder="Sinopsis tentang buku ini" name="sinopsis" id="floatingTextarea2" style="height: 100px"></textarea>
                    <label for="floatingTextarea2">Sinopsis</label>
                </div>
                <input class="btn btn-success" type="submit" value="Submit">
                <input type="reset" class="btn btn-warning text-light" value="Reset">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
