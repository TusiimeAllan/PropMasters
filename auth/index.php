<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/x-icon" href="../assets/images/logo/favicon.ico">

  <title>Login | PropMasters</title>

  <!-- Loading the CSS Code -->
  <link rel="stylesheet" href="./../assets/css/bootstrap.min.css">
  <style>
    .auth-container {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background: linear-gradient(45deg, navy, white);
    }

    .login-image {
      flex: 1;
      background-image: url('./../assets/images/property/4.jpg');
      background-size: cover;
      background-position: center;
      height: 100vh;
    }

    .login-form {
      flex: 1;
      padding: 20px;
    }

    .signup-form {
      display: none;
      padding: 20px;
    }

    .logo {
      display: block;
      margin: 0 auto;
      max-width: 200px;
      height: auto;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row auth-container">
      <div class="col-md-6 login-image"></div>
      <div class="col-md-6">
        <a href="./../index.php">
          <img src="../assets/images/logo/logo.png" alt="Logo" class="logo">
        </a>

        <h6 style="text-align: center; color: 
        <?php if (isset($_SESSION['color'])) {
          echo $_SESSION['color'];
        } else {
          echo "white";
        } ?>;">
          <?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
          } ?></h6>

        <form method="POST" action="authentication.php" class="login-form" id="login-form">
          <h2>Login</h2>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password">
          </div><br />
          <input type="hidden" name="login" value="true" />
          <input type="submit" class="btn btn-primary" value="Login">
          <button type="button" class="btn btn-link" id="signup-btn">Sign Up</button>
        </form>
        <form method="POST" action="authentication.php" class="signup-form" id="signup-form">
          <h2>Sign Up</h2>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Enter username">
          </div>
          <div class="form-group">
            <label for="contact">Contact</label>
            <input type="number" class="form-control" name="contact" placeholder="Enter contact">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password">
          </div>
          <div class="form-group">
            <label for="account-type">Account Type</label>
            <select class="form-control" name="account-type">
              <option value="user">User</option>
              <option value="landlord">Landlord</option>
            </select>
          </div><br />
          <input type="hidden" name="register" value="true" />
          <input type="submit" class="btn btn-primary" value="Sign Up" />
          <button type="button" class="btn btn-link" id="login-btn">Back to Login</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("signup-btn").addEventListener("click", function() {
      document.getElementById("login-form").style.display = "none";
      document.getElementById("signup-form").style.display = "block";
    });

    document.getElementById("login-btn").addEventListener("click", function() {
      document.getElementById("signup-form").style.display = "none";
      document.getElementById("login-form").style.display = "block";
    });
  </script>

  <!-- Loading the Javascript Code -->
  <script src="./../assets/js/bootstrap.min.js"></script>
</body>

</html>