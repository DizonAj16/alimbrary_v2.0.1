<?php
// Start session
session_start();

// Check if the user is logged in and is a user, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
  header("location: login.php");
  exit;
}

// Set the welcome message
$welcome_message = "Welcome, User!";

// Include config file
require_once "config.php";

// Fetch user's profile image path from the database
$user_id = $_SESSION["id"];
$sql = "SELECT image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $profile_image);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
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
  <link rel="stylesheet" href="navigation.css">
  <link rel="stylesheet" href="fa-css/all.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
    }


    .centered-message {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      background: rgba(255, 255, 255, 0.8);
      padding: 10px;
      border-radius: 10px;
    }

    .centered-message h1 {
      font-size: 2rem;
      color: black;
      font-weight: bold;
    }

    .centered-message span {
      font-family: "Gill Sans", sans-serif;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      font-size: 2rem;
      color: #800000;
      letter-spacing: 2px;
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
        <img src="Images/zppsulibrary.jpg" alt="Los Angeles" class="d-block" style="width: 100%; height: 100vh;">
      </div>
      <div class="carousel-item">
        <img src="Images/zppsulibrary.jpg" alt="Chicago" class="d-block" style="width: 100%; height: 100vh;">
      </div>
      <div class="carousel-item">
        <img src="Images/zppsulibrary.jpg" alt="New York" class="d-block" style="width: 100%; height: 100vh;">
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
      <div class="title p-1">
        <img class="logo" src="Images/logo.png" alt="">
      </div>

      <!-- Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="userdashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="userbook.php"><i class="fa fa-book fa-lg"></i> Browse Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="borrowedbooks.php"><i class="fas fa-book-reader fa-lg"></i> Borrowed Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="userreturnhistory.php"><i class="fa fa-history fa-lg"></i> Returned Books</a>
          </li>
        </ul>

        <span class="navbar-text mx-3"><?php echo $welcome_message; ?></span>
        
        <!-- Dropdown -->
        <div class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php
              // Display user's profile image or default user icon
              if (!empty($profile_image)) {
                echo '<img src="' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="rounded-circle" style="width: 32px; height: 32px;">';
              } else {
                echo '<i class="fa fa-user fa-lg"></i>';
              }
              ?>
              <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
              <li><a class="dropdown-item" href="reset-password.php"><i class="fas fa-unlock"></i> Reset Password</a></li>
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

  <!-- Centered message -->
  <div class="centered-message">
    <h1>Welcome to the <span class="highlight">ZPPSU AlimBrary</span> Management System</h1>
  </div>

</body>

</html>