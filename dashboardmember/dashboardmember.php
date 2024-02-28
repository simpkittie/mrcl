<?php
session_start();

if(!isset($_SESSION["user_id"])) {
  header("Location: ../login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>User Dashboard</title>
    <style>
        body {
            background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cfff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);
            margin-top: 70px; /* Add margin-top to create space for the navbar */
            height: 100vh; /* Set height to 100% of viewport height */
        }

        .navbar-brand {
            position: absolute;
            top: 0;
            left: 0;
            margin: 10px; /* Add margin to adjust positioning */
        }

        .dropdown {
            position: absolute;
            top: 25px;
            right: 0;
        }

        .dropdown-menu {
            margin-top: 10px;
        }

        .cardImg {
            width: 600px;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .cardImg button {
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
        }

        .cardImg img {
            width: 100%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .cardImg img:hover {
            transform: scale(1.05);
        }
        

        @media screen and (max-width: 600px) {
            .d-flex flex-wrap gap-2 cardImg a img {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <a class="navbar-brand">
        <img src="../assets/logo2.png" alt="logo" width="100px">
    </a>
    
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../assets/memberlogo.png" alt="memberLogo" width="40px">
        </button>
        <ul class="dropdown-menu position-absolute mt-2 p-2">
            <li>
                <a class="dropdown-item text-center" href="#">
                    <img src="../assets/memberlogo.png" alt="adminLogo" width="30px">
                </a>
            </li>
            <div class="alert alert-success" role="alert">Selamat datang - <span class="fw-bold text-capitalize"><?php echo $_SESSION['username']; ?></span> di Dashboard EPerpus</div>
            <hr>         
            <li>
                <a class="dropdown-item text-center p-2 bg-danger text-light rounded" href="signout.php">Sign Out <i class="fa-solid fa-right-to-bracket"></i></a>
            </li>
        </ul>
    </div>

    <div class="mt-5 p-4">
        <?php
        $date = date('Y-m-d H:i:s');
        $day = date('l');
        $dayOfMonth = date('d');
        $month = date('F');
        $year = date('Y');
        ?>

        <h1 class="mt-5 fw-bold">Dashboard - <span class="fs-4 text-primary"> <?php echo $day. " ". $dayOfMonth." ". " ". $month. " ". $year; ?> </span></h1>
    
        <div class="alert alert-warning" role="alert">Selamat datang - <span class="fw-bold text-capitalize"><?php echo $_SESSION['username']; ?></span> di Dashboard EPerpus</div>

        <div class="mt-3 p-3">
            <div class="mt-2 mb-4">
                <h3 class="mb-3">Layanan Perpustakaan yang tersedia</h3>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <div class="cardImg">
                    <a href="buku/daftarbuku.php">
                        <img src="../assets/buku.png" alt="daftar buku" style="max-width: 100%;" width="600px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="buku/detailpeminjaman.php">
                        <img src="../assets/pinjam.png" alt="daftar buku" style="max-width: 100%;" width="600px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="buku/koleksibuku.php">
                        <img src="../assets/koleksii.png" alt="daftar buku" style="max-width: 100%;" width="600px">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
