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

$query_kategori = "SELECT * FROM kategoribuku";
$result_kategori = mysqli_query($conn, $query_kategori);
$kategoribuku = mysqli_fetch_all($result_kategori, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $sinopsis = $_POST['sinopsis'];
    $kategori_id = $_POST['kategori'];

    $query_update = "UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun_terbit=?, sinopsis=? WHERE id=?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sssssi", $judul, $penulis, $penerbit, $tahun_terbit, $sinopsis, $id_buku);
    if ($stmt_update->execute()) {
        $query_update_kategori = "UPDATE kategoribuku_relasi SET kategori_id=? WHERE buku_id=?";
        $stmt_update_kategori = $conn->prepare($query_update_kategori);
        $stmt_update_kategori->bind_param("ii", $kategori_id, $id_buku);
        $stmt_update_kategori->execute();

        header("Location: daftarbuku.php");
        exit;
    } else {
        header("Location: error.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Buku || Admin</title>
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
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Update Buku</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" value="<?= $buku['judul']; ?>">
        </div>
        <div class="mb-3">
            <label for="penulis" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="penulis" name="penulis" value="<?= $buku['penulis']; ?>">
        </div>
        <div class="mb-3">
            <label for="penerbit" class="form-label">Penerbit</label>
            <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= $buku['penerbit']; ?>">
        </div>
        <div class="mb-3">
            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
            <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?= $buku['tahun_terbit']; ?>">
        </div>
        <div class="mb-3">
            <label for="sinopsis" class="form-label">Sinopsis</label>
            <textarea class="form-control" id="sinopsis" name="sinopsis"><?= $buku['sinopsis']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" id="kategori" name="kategori">
            <?php foreach ($kategoribuku as $kategori) : ?>
            <?php
            $selected = '';
            if (isset($buku['kategori_id']) && $kategori['id'] == $buku['kategori_id']) {
                $selected = 'selected';
            }
            ?>
            <option value="<?= $kategori['id']; ?>" <?= $selected; ?>><?= $kategori['nama_kategori']; ?></option>
        <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
