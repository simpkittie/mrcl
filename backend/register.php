<?php
 require('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_lengkap = $_POST['fullname'];
    $nama_pengguna = $_POST['username'];
    $email = $_POST['email'];
    $kata_sandi = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = $_POST['alamat'];
    $role = "peminjam";

    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$nama_pengguna' OR email='$email'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Username or email already exists. Please choose a different one.'); window.location.href='../register/register.html';</script>";
        exit();
    } else {
        $register_query = mysqli_query($conn, "INSERT INTO user (fullname, username, email, password, alamat, role) VALUES ('$nama_lengkap', '$nama_pengguna', '$email', '$kata_sandi', '$alamat', '$role')");

        if ($register_query) {
            echo "<script>alert('Registration successful. You can now log in.'); window.location.href='../login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Registration failed. Please try again.'); window.location.href='../register/register.html';</script>";
            exit();
        }
    }
}
?>
