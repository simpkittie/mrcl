<?php
session_start(); // Mulai sesi di awal skrip

require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan semua input yang diterima di-escape untuk mencegah SQL injection
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun-terbit']);
    $sinopsis = mysqli_real_escape_string($conn, $_POST['sinopsis']);
    $kategori_nama = mysqli_real_escape_string($conn, $_POST['kategori']);

    // Periksa apakah gambar diunggah dan tidak ada kesalahan
    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] === UPLOAD_ERR_OK) {
        // Periksa apakah kategori buku tersedia dalam database
        $query_kategori = mysqli_query($conn, "SELECT id FROM kategoribuku WHERE nama_kategori = '$kategori_nama'");
        $result_kategori = mysqli_fetch_assoc($query_kategori);
        if (!$result_kategori) {
            $_SESSION['error_message'] = "Error: Kategori buku tidak valid.";
            header("Location: ../tambah-barang.php");
            exit;
        }
        $kategori_id = $result_kategori['id'];

        // File upload related variables
        $nama_file = strtolower(str_replace(' ', '_', $judul));
        $kode_unik = uniqid();
        $ext = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
        $gambar_nama = $nama_file . '_' . $kode_unik . '.' . $ext;
        $gambar_destinasi = "../imgDB/" . $gambar_nama;

        // Lakukan operasi INSERT hanya jika koneksi ke database berhasil
        if ($conn) {
            $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, cover, sinopsis) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $judul, $penulis, $penerbit, $tahun_terbit, $gambar_nama, $sinopsis);

            if ($stmt->execute()) {
                // Ambil ID dari buku yang baru saja dimasukkan
                $buku_id = $stmt->insert_id;

                // Sisipkan relasi antara buku dan kategori
                $stmt_relasi = $conn->prepare("INSERT INTO kategoribuku_relasi (buku_id, kategori_id) VALUES (?, ?)");
                $stmt_relasi->bind_param("ii", $buku_id, $kategori_id);

                if ($stmt_relasi->execute()) {
                    // Pindahkan file gambar ke lokasi yang diinginkan
                    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $gambar_destinasi)) {
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
        } else {
            $_SESSION['error_message'] = "Koneksi ke database gagal: " . mysqli_connect_error();
        }
    } else {
        $_SESSION['error_message'] = "Error: Gambar tidak ditemukan atau terjadi kesalahan saat mengunggah gambar.";
    }

    // Jika ada kesalahan, arahkan kembali ke halaman tambah-barang.php
    header("Location: ../tambah-barang.php");
    exit;
}
?>
