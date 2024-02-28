<?php
 require('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_email = $_POST['username_email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username_email' OR email='$username_email'");

    if (mysqli_num_rows($query) == 1) {
        $user_data = mysqli_fetch_assoc($query);

        if (password_verify($password, $user_data['password'])) {
            session_start();
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            
            if ($user_data['role'] == 'admin') {  
                header("Location:../dashboardadmin/dashboardadmin.php");
                exit();
            } elseif ($user_data['role'] == 'petugas') { 
                header("Location:../dashboardpetugas/dashboardpetugas.php");
                exit();
            } else {
                header("Location:../dashboardmember/dashboardmember.php");
                exit();
            }
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='../login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid username or email'); window.location.href='../login.php';</script>";
        exit();
    }
}
?>