<?php
// Start session
session_start();

// Check if the user is logged in and is an admin, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script defer src="js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="titlestyle.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="navstyle.css">
  <link rel="stylesheet" href="fa-css/all.css">
  <style>
    body{
      font-family: 'Arial', sans-serif;
    }
  </style>
</head>

<body style="position: relative;">

  <!-- Carousel -->
  <div id="demo" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicators/dots -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
    </div>

    <!-- The slideshow/carousel -->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="Images\custom-upload-1681454804.webp" alt="Los Angeles" class="d-block" style="width: 100%; height: 100vh;">
      </div>
      <div class="carousel-item">
        <img src="Images\istockphoto-1437365584-1024x1024.jpg" alt="Chicago" class="d-block" style="width: 100%; height: 100vh;">
      </div>
      <div class="carousel-item">
        <img src="Images\School-Library-Management-Software-01-1024x555.png" alt="New York" class="d-block" style="width: 100%; height: 100vh;">
      </div>
    </div>

    <!-- Left and right controls/icons -->
    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container-fluid">
      <div class="title rounded-3 p-1">
        <span class="letter-a">A</span>
        <span class="letter-l">l</span>
        <span class="letter-i">i</span>
        <span class="letter-m">m</span>
        <span class="letter-b">b</span>
        <span class="letter-r">r</span>
        <span class="letter-a">a</span>
        <span class="letter-r">r</span>
        <span class="letter-y">y</span>
        <img src="Images/icons8-book-50.png" alt="" style="margin-left: 5px;">
      </div>

      <!-- Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="userbook.php"><i class="fa fa-book fa-lg"></i> Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="borrowedbooks.php"><i class="fas fa-book-reader fa-lg"></i> Borrowed Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="userreturnhistory.php"><i class="fa fa-book fa-lg"></i> Returned Books</a>
          </li>
        </ul>

        <!-- Dropdown -->
        <div class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-user fa-lg"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
              <li><a class="dropdown-item" href="reset-password.php"><i class="fas fa-undo"></i> Reset Password</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
            </ul>
          </li>
        </div>
      </div>
    </div>
  </nav>

</body>

</html>