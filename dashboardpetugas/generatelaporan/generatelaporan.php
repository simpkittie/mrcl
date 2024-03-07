<?php
$conn = mysqli_connect("localhost", "root", "", "eperpus");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_peminjaman = "SELECT COUNT(*) as total_peminjaman FROM peminjaman";
$result_peminjaman = $conn->query($sql_peminjaman);
$row_peminjaman = $result_peminjaman->fetch_assoc();
$total_peminjaman = $row_peminjaman['total_peminjaman'];

$sql_member_peminjam = "SELECT COUNT(*) as total_peminjam FROM user WHERE role = 'peminjam'";
$result_member_peminjam = $conn->query($sql_member_peminjam);
$row_member_peminjam = $result_member_peminjam->fetch_assoc();
$total_peminjam = $row_member_peminjam['total_peminjam'];

$sql_user_paling_banyak_meminjam = "SELECT u.fullname, COUNT(*) as total_peminjaman_user 
                                    FROM peminjaman p 
                                    JOIN user u ON p.userid = u.id 
                                    GROUP BY u.id 
                                    ORDER BY total_peminjaman_user DESC 
                                    LIMIT 1";
$result_user_paling_banyak_meminjam = $conn->query($sql_user_paling_banyak_meminjam);
$row_user_paling_banyak_meminjam = $result_user_paling_banyak_meminjam->fetch_assoc();
$user_paling_banyak_meminjam = $row_user_paling_banyak_meminjam['fullname'];
$total_peminjaman_user = $row_user_paling_banyak_meminjam['total_peminjaman_user'];

$sql_buku_paling_banyak_dipinjam = "SELECT b.judul, COUNT(*) as total_peminjaman_buku 
                                    FROM peminjaman p 
                                    JOIN buku b ON p.bukuid = b.id 
                                    GROUP BY b.id 
                                    ORDER BY total_peminjaman_buku DESC 
                                    LIMIT 1";
$result_buku_paling_banyak_dipinjam = $conn->query($sql_buku_paling_banyak_dipinjam);
$row_buku_paling_banyak_dipinjam = $result_buku_paling_banyak_dipinjam->fetch_assoc();
$buku_paling_banyak_dipinjam = $row_buku_paling_banyak_dipinjam['judul'];
$total_peminjaman_buku = $row_buku_paling_banyak_dipinjam['total_peminjaman_buku'];

$sql_kategori_buku = "SELECT kb.nama_kategori, COUNT(*) as total_buku_kategori 
                      FROM kategoribuku_relasi kr 
                      JOIN kategoribuku kb ON kr.kategori_id = kb.id 
                      GROUP BY kb.id";
$result_kategori_buku = $conn->query($sql_kategori_buku);

$sql_buku_paling_banyak_disimpan = "SELECT b.judul, COUNT(*) as total_koleksi 
                                    FROM koleksi k 
                                    JOIN buku b ON k.bukuid = b.id 
                                    GROUP BY b.id 
                                    ORDER BY total_koleksi DESC 
                                    LIMIT 1";
$result_buku_paling_banyak_disimpan = $conn->query($sql_buku_paling_banyak_disimpan);
$row_buku_paling_banyak_disimpan = $result_buku_paling_banyak_disimpan->fetch_assoc();
$buku_paling_banyak_disimpan = $row_buku_paling_banyak_disimpan['judul'];
$total_koleksi_buku = $row_buku_paling_banyak_disimpan['total_koleksi'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous" rel="stylesheet">
    <style>
        body {
            background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            height: 100vh;
        }
        .title {
            text-align: center;
            color: #fff;
            margin-bottom: 2rem;
            text-shadow: 0 0 3px black;
        }
        .layout-table {
            margin-top: 20px;
        }
        .layout-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .layout-table th, .layout-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-weight: normal;
            background-color: #d0bfff;
        }
        .layout-table th {
            background-color: whitesmoke;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }
        .layout-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .layout-table tbody tr:hover {
            background-color: #ddd;
        }
        .layout-table ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .layout-table ul li {
            margin-bottom: 4px;
        }
        @media print {
            .print-button, .back-btn {
                display: none !important;
            }
        }
        .back-btn {
            text-align: center;
            margin-bottom: 20px;            
            margin-left: 40px;
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
        .print-button {
            background-color: #69d5ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: block;
            margin: auto;
        }
        .print-button:hover {
            background-color: #d0bfff;
        }
    </style>
</head>
<body>
    <a href="../dashboardpetugas.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
    <div class="container p-4 mt-4">
        <h2 class="title">Generate Laporan</h2>
        <div class="layout-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Peminjaman</td>
                        <td><?php echo $total_peminjaman; ?></td>
                    </tr>
                    <tr>
                        <td>Total Member</td>
                        <td><?php echo $total_peminjam; ?></td>
                    </tr>
                    <tr>
                        <td>User Paling Banyak Meminjam</td>
                        <td><?php echo $user_paling_banyak_meminjam; ?> (Total Peminjaman: <?php echo $total_peminjaman_user; ?>)</td>
                    </tr>
                    <tr>
                        <td>Buku Paling Banyak Dipinjam</td>
                        <td><?php echo $buku_paling_banyak_dipinjam; ?> (Total Peminjaman: <?php echo $total_peminjaman_buku; ?>)</td>
                    </tr>
                    <tr>
                        <td>Kategori Buku</td>
                        <td>
                            <ul>
                                <?php while($row_kategori_buku = $result_kategori_buku->fetch_assoc()): ?>
                                    <li><?php echo $row_kategori_buku['nama_kategori']; ?>: <?php echo $row_kategori_buku['total_buku_kategori']; ?> buku</li>
                                <?php endwhile; ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>Buku Paling Banyak Disimpan</td>
                        <td><?php echo $buku_paling_banyak_disimpan; ?> (Total Koleksi: <?php echo $total_koleksi_buku; ?>)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="javascript:void(0)" class="print-button" onclick="window.print()">Cetak Laporan</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
