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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku</title>
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
            margin-top: 20px;
        }
        .img-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .img-container img {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Pinjam Buku</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="img-container">
                <img src="../../imgDB/<?= $buku["cover"]; ?>" alt="Gambar Buku">
            </div>
        </div>
        <div class="col-md-8">
            <h3>Detail Buku</h3>
            <form>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="<?= $buku['judul']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" value="<?= $buku['penulis']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= $buku['penerbit']; ?>" readonly>
                </div>
            </form>
            <h3>Form Peminjaman</h3>
            <form action="../../backend/pinjam.php" method="POST">
                <input type="hidden" name="userid" value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                <input type="hidden" name="bukuid" value="<?= $buku['id']; ?>">
                <div class="mb-3">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Peminjaman</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_peminjaman" min="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian (Maksimal 7 hari)</label>
                    <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required>
                </div>
                <button type="submit" class="btn btn-success">Pinjam</button>
                <a href="daftarbuku.php" class="btn btn-danger ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
function hitungTanggalPengembalian() {
    var tanggalPinjam = new Date(document.getElementById("tanggal_pinjam").value);
    var tanggalPengembalian = new Date(tanggalPinjam);
    tanggalPengembalian.setDate(tanggalPengembalian.getDate() + 7);
    var tanggalHariIni = new Date();
    if (tanggalPengembalian < tanggalHariIni) {
        tanggalPengembalian = new Date(tanggalHariIni);
    }
    var tanggalFormatted = tanggalPengembalian.toISOString().slice(0, 10);
    document.getElementById("tanggal_pengembalian").setAttribute("min", tanggalPinjam.toISOString().slice(0, 10));
    document.getElementById("tanggal_pengembalian").setAttribute("max", tanggalFormatted);
}

document.getElementById("tanggal_pinjam").addEventListener("change", hitungTanggalPengembalian);

hitungTanggalPengembalian();
</script>
</body>
</html>
