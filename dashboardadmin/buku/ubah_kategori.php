<?php
$conn = mysqli_connect("localhost", "root", "", "eperpus");
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_kategori = $_POST['nama_kategori'];

    $query = mysqli_query($conn, "UPDATE kategoribuku SET nama_kategori='$nama_kategori' WHERE id=$id");

    if ($query) {
        echo "<script>alert('Update Data Berhasil');window.location.href='kategori.php'</script>";
        exit();
    } else {
        echo "<script>alert('Update Data Gagal');window.location.href='kategori.php'</script>";
        exit();
    }
}

$id_kategori = $_GET['id'];

$query_kategori = mysqli_query($conn, "SELECT * FROM kategoribuku WHERE id=$id_kategori");
$row_kategori = mysqli_fetch_assoc($query_kategori);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Kategori</title>
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<h1 class="mt-4">UPDATE KATEGORI</h1>
<div class="card">
    <div class="cardbody">
        <div class="row">
            <form method="post">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col-md-2">Nama Kategori</div>
                        <div class="col-md-8">
                            <input type="text" name="nama_kategori" class="form-control"
                            value="<?php echo $row_kategori['nama_kategori']; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <button type="submit" class="btn btn-primary" name="submit" value="submit">Update</button>
                            <a href="kategori.php" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
