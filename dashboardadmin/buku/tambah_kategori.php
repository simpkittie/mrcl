<?php
session_start();

if(!isset($_SESSION["user_id"]) ) {
  header("Location: ../../login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Buku</title>
    <link rel="stylesheet" href="../../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .col-md-2 {
            font-size: 30px; 
            color: purple;
            font-weight: bold; 
        }
    </style>
</head>
<body>
    <h1 class="mt-4">Tambah Kategori Buku</h1>
    <div class="card">
        <div class="cardbody">
            <div class="row">
                <form method="post">
                    <?php
                    $conn=mysqli_connect("localhost","root","","eperpus"); 

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $kategoribuku = $_POST['nama_kategori'];        
                        $query = mysqli_query($conn, "INSERT INTO kategoribuku (nama_kategori) VALUES ('$kategoribuku')");
                        if($query){
                            echo "<script>alert('Tambah Data Berhasil');window.location.href='kategori.php'</script>";
                            exit();
                        } else{
                            echo "<script>alert('Tambah Data Gagal');window.location.href='kategori.php'</script>"; 
                            exit();                                   
                        }
                    }                                              
                    ?>
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-2">Nama Kategori</div>
                            <div class="col-md-8"><input type="text" name="nama_kategori" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary" name="submit" value="submit">Simpan</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <a href="kategori.php" class="btn btn-danger btn-kembali">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
