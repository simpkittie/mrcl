<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] === UPLOAD_ERR_OK) {
        $judul = $_POST['judul'];
        $penulis = $_POST['penulis'];
        $penerbit = $_POST['penerbit'];
        $tahun_terbit = $_POST['tahun-terbit'];
        $sinopsis = $_POST['sinopsis'];

        $kategori_nama = $_POST['kategori'];
        $query_kategori = mysqli_query($conn, "SELECT id FROM kategoribuku WHERE nama_kategori = '$kategori_nama'");
        $result_kategori = mysqli_fetch_assoc($query_kategori);
        $kategori_id = $result_kategori['id'];

        $nama_file = strtolower(str_replace(' ', '_', $judul));
        $kode_unik = uniqid();
        
        if (isset($_FILES["gambar"]["tmp_name"]) && $_FILES["gambar"]["error"] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
            $gambar_nama = $nama_file . '_' . $kode_unik . '.' . $ext;
            $gambar_tmp = $_FILES["gambar"]["tmp_name"];
            $gambar_destinasi = "C:/xampp/htdocs/ukkmarcel/imgDB/" . $gambar_nama;
        } else {
            session_start();
            $_SESSION['error_message'] = "Error: Terjadi masalah saat mengunggah gambar.";
            header("Location: ../tambah-barang.php");
            exit;
        }

        $query_cek_buku = "SELECT id FROM buku WHERE judul = '$judul'";
        $result_cek_buku = mysqli_query($conn, $query_cek_buku);

        if (mysqli_num_rows($result_cek_buku) > 0) {
            session_start();
            $_SESSION['error_message'] = "Error: Buku dengan judul yang sama sudah ada.";
            header("Location: ../tambah-barang.php");
            exit;
        }

        if (!$conn) {
            die("Koneksi ke database gagal: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, cover, sinopsis) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $judul, $penulis, $penerbit, $tahun_terbit, $gambar_nama, $sinopsis);

        if ($stmt->execute()) {
            $buku_id = $stmt->insert_id;
            $stmt_relasi = $conn->prepare("INSERT INTO kategoribuku_relasi (buku_id, kategori_id) VALUES (?, ?)");
            $stmt_relasi->bind_param("ii", $buku_id, $kategori_id);

            if ($stmt_relasi->execute()) {
                if (move_uploaded_file($gambar_tmp, $gambar_destinasi)) {
                    session_start();
                    $_SESSION['success_message'] = "Data berhasil disimpan.";
                    $stmt_relasi->close();
                    $stmt->close();
                    mysqli_close($conn);
                    header("Location: ../dashboardadmin/buku/tambahBuku.php");
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error: Gagal memindahkan file gambar.";
                }
            } else {
                $_SESSION['error_message'] = "Error inserting into kategoribuku_relasi: " . $stmt_relasi->error;
            }
        } else {
            $_SESSION['error_message'] = "Error inserting into buku: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($conn);
    } else {
        session_start();
        $_SESSION['error_message'] = "Error: Gambar tidak ditemukan.";
        header("Location: ../tambah-barang.php");
        exit;
    }
}
?>
