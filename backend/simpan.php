<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eperpus';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if(isset($_POST['simpan'])){
    $userid = $_POST['userid'];
    $bukuid = $_POST['bukuid'];

    $check_query = "SELECT * FROM koleksi WHERE userid = '$userid' AND bukuid = '$bukuid'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "<script>alert('Buku sudah ada dalam koleksi.'); window.location.href='../dashboardmember/buku/daftarbuku.php';</script>";
        exit();
    } else {
        $query = "INSERT INTO koleksi (userid, bukuid) VALUES ('$userid', '$bukuid')";
        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Buku Telah Ditambahkan ke Koleksi'); window.location.href='../dashboardmember/buku/daftarbuku.php';</script>";
            exit();
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
