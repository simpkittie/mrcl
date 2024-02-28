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
  <link rel="stylesheet" href="../../styles.css">
  <title>Kategori Buku</title>  
</head>
<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<h1 class="mt-4">KATEGORI BUKU</h1>
<div class="row">
  <div class="col-md-12">    
    <a href="tambah_kategori.php" class="btn btn-danger">+ Tambah Data</a>
    <table class="table table-striped custom-table table-bordered" id="dataTable" width="100%" cellspacing="0" style="background-color: #fdbb8a;">
      <tr>  
        <th>No</th>
        <th>Nama Kategori</th>
        <th>Aksi</th>        
      </tr>
      <?php
        $conn=mysqli_connect("localhost","root","","eperpus"); 

        // Query untuk mengambil data kategoribuku dari database
        $query = "SELECT * FROM kategoribuku";
        $result = mysqli_query($conn, $query);
        
        // Mengecek apakah query berhasil dijalankan
        if($result && mysqli_num_rows($result) > 0) {
            $no = 1; // Variabel untuk menampilkan nomor urut
            // Looping untuk menampilkan data per baris
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$no."</td>"; // Menampilkan nomor urut
                echo "<td>".$row['nama_kategori']."</td>"; // Menampilkan nama kategori
                echo "<td>
                        <a href='ubah_kategori.php?id=".$row['id']."' class='btn btn-warning'>Ubah</a>
                        <a href='hapus_kategori.php?id=".$row['id']."' class='btn btn-danger'>Hapus</a>
                      </td>";              
                $no++; // Increment nomor urut
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data kategori.</td></tr>"; // Pesan jika tidak ada data kategori
        }

        // Menutup koneksi database
        mysqli_close($conn);
      ?>
    </table>
  </div>
</div>
<div class="row justify-content-end mt-3">
  <div class="col-md-6 text-md-end">
    <a href="tambahBuku.php" class="btn btn-primary btn-cyan">Kembali</a> <!-- Tombol kembali dipindahkan ke sini -->
  </div>
</div>
</body>
</html>
