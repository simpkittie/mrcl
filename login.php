<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>Eperpus</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-image: radial-gradient(circle at 50% -13.44%, #48dce7 0, #46dbf0 3.33%, #49daf8 6.67%, #51d9ff 10%, #5cd7ff 13.33%, #69d5ff 16.67%, #78d2ff 20%, #87cff 23.33%, #97ccff 26.67%, #a6c9ff 30%, #b5c6ff 33.33%, #c3c2ff 36.67%, #d0bfff 40%, #ddbbff 43.33%, #e8b8fa 46.67%, #f2b5f2 50%, #fbb2e9 53.33%, #ffb0e0 56.67%, #ffafd7 60%, #ffaecd 63.33%, #ffadc3 66.67%, #ffaeb9 70%, #ffafb0 73.33%, #ffb0a7 76.67%, #ffb29e 80%, #ffb597 83.33%, #ffb890 86.67%, #fdbb8a 90%, #f6be86 93.33%, #efc283 96.67%, #e6c581 100%);">
  <div class="container p-5"> 
    <div class="d-flex justify-content-center">
      <div class="card" style="width: 30rem; background: whitesmoke;">
        <img src="assets/logo.png" class="card-body pt-2" alt="logo">
        <hr>
        <div class="card-body pt-2">
          <h3 class="card-text text-center">Portal Login EPerpus</h3>
        </div>          
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Eperpus</title>
  <link rel="stylesheet" href="register/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <div class="title">Login</div>
    <div class="content">
      <form action="backend/login.php" method="post">
        <div class="user-details">
          <div class="input-box">
            <span class="details">Username/Email</span>
            <input type="text" placeholder="Enter your username/email" required name="username_email">
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" placeholder="Enter your password" required name="password">
          </div>
          <p>Don't have an account yet? <a href="register/register.html" class="text-decoration-none text-primary">Sign Up</a></p>
        </div>
        <div class="button">
          <input type="submit" value="Login">
        </div>
      </form>
    </div>
  </div>
</body>
</html>
