<?php
require_once "../config.php";
// Check if the user is logged in and is an admin, if not then redirect to login page
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
  header("location: ../login.php");
  exit;
}

// Retrieve user's profile image
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
  <title>Dashboard</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <script defer src="../js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../external-css/navigation.css">
  <link rel="stylesheet" href="../fa-css/all.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: url('../images/glassmorphism.jpeg');
      height: 100vh;
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }

    .dashboard-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }

    .dashboard-section {
      flex: 1 1 350px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.06);
      border: none;
      box-shadow: 20px 20px 22px rgba(0, 0, 0, 0.02);
      backdrop-filter: blur(20px);
      transition: background-color 0.3s ease;
    }

    .dashboard-section:hover {
      cursor: pointer;
      background-color: rgba(0, 0, 0, 0.1);
    }

    .dashboard-section i {
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .dashboard-section {
        flex-basis: calc(100% - 40px);
      }
    }

    .recent-icon,
    .borrow-icon,
    .return-icon,
    .warning-icon {
      height: 70px;
      width: 70px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

    <div class="container-fluid">
      <div class="title p-1">
        <img class="logo" src="../Images/logo.png" alt="">
      </div>

      <!-- Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link " href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="userdashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
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



        <!-- Dropdown -->
        <div class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php
              // Display user's profile image or default user icon
              if (!empty($profile_image)) {
                echo '<img src="../' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="rounded-circle" style="width: 32px; height: 32px;">';
              } else {
                echo '<i class="fa fa-user fa-lg"></i>';
              }
              ?>
              <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
              <li><a class="dropdown-item" href="../reset-password.php"><i class="fas fa-unlock"></i> Reset Password</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
            </ul>
          </li>
        </div>
      </div>
    </div>
  </nav>

  <div class="container" style="margin-top: 100px;">
    <h1 class="text-center fw-bold text-light">Dashboard</h1>
  </div>

  <div class="dashboard-container" id="dashboard">

    <a class="total-available-books dashboard-section text-light" href="userbook.php" style="text-decoration: none;">
      <img src="../images/icons8-books-96.png" alt="">
      <?php include '../dashboard-includes/total_available_books.php'; ?>
    </a>


    <a href="borrowedbooks.php" class="dashboard-section text-light" style="text-decoration: none;">
      <img src="../images/icons8-most-recent-58.png" alt="" class="recent-icon">
      <?php include '../dashboard-includes/user_currently_borrowed.php'; ?>
    </a>


    <div class="dashboard-section text-light">
      <img src="../images/icons8-borrow-book-64.png" class="borrow-icon">
      <?php include '../dashboard-includes/total_user_borrowed.php'; ?>
    </div>

    <a href="userreturnhistory.php" class="dashboard-section text-light" style="text-decoration: none;">
      <img src="../images/icons8-return-book-64.png" class="return-icon">
      <?php include '../dashboard-includes/total_user_returned.php'; ?>
    </a>


    <div class="dashboard-section text-light">
      <img src="../images/icons8-today-94.png" alt="">
      <?php include '../dashboard-includes/total_user_borrowed_today.php'; ?>
    </div>


    <a href="borrowedbooks.php" class="dashboard-section  text-light" style="text-decoration: none;">
      <img src="../images/icons8-warning-48.png" alt="" class="warning-icon">
      <?php include '../dashboard-includes/user_overdue_books.php'; ?>
    </a>




    <div class="dashboard-section  text-light">
      <img src="../images/icons8-star-94.png" alt="">
      <h3 class="fw-bold" id="title">My Favorite Books</h3>
      <div id="userMostBorrowedBooks" style="display: none;">
        <?php include '../dashboard-includes/user_most_borrowed_books.php'; ?>
      </div>
      <button class="btn btn-sm btn-transparent text-light fw-bold" id="expandButton" onclick="toggleCollapse('userMostBorrowedBooks')"> <i class="fas fa-chevron-down fa-lg"></i> Click to expand</button>
      <script>
        function toggleCollapse(elementId) {
          var element = document.getElementById(elementId);
          var button = document.getElementById("expandButton");
          var title = document.getElementById("title");

          if (element.style.display === "none") {
            element.style.display = "block";
            button.innerHTML = '<i class="fas fa-chevron-up fa-lg"></i> Click to collapse';
            title.style.display = "none";
          } else {
            element.style.display = "none";
            button.innerHTML = '<i class="fas fa-chevron-down fa-lg"></i> Click to expand';
            title.style.display = "block";
          }
        }
      </script>

    </div>


    <a href="../myprofile.php" class="dashboard-section text-light" style="text-decoration: none;">
      <div class="text-center">
        <img src="../images/icons8-save-as-94.png" alt="">
        <h2><i>Edit your profile!</i></h2>
      </div>
    </a>
  </div>

  <footer style="background-color: black;">
    <marquee behavior="scroll" direction="left" style="font-family: 'Arial', sans-serif; font-size: 24px; color: #ffffff; font-weight: bold;">
      <span style="color: #ff0000;">&#169; <?php echo date("Y"); ?></span> <span style="color: #1e90ff;">Alimbrary</span>
    </marquee>
  </footer>


</body>

</html>