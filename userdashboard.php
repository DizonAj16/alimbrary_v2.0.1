<?php
require_once "config.php";
// Check if the user is logged in and is an admin, if not then redirect to login page
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: login.php");
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

        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .dashboard-section {
            flex: 1 1 290px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            background-color: #fff;
        }

        .dashboard-section:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            cursor: pointer;
        }

        .dashboard-section i {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .dashboard-section {
                flex-basis: calc(100% - 40px);
            }
        }
    </style>
</head>

<body>

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
                        <a class="nav-link" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="userdashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
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

    <div class="dashboard-container" id="dashboard" style="padding-top: 90px;">


        <div class="total-available-books dashboard-section bg-info text-white">
            <i class="fab fa-readme fa-3x"></i>
            <?php include 'total_available_books.php'; ?>
        </div>

        <a href="borrowedbooks.php" class="dashboard-section bg-primary text-white" style="text-decoration: none;">
            <i class="fas fa-book-open fa-3x"></i>
            <?php include 'user_currently_borrowed.php'; ?>
        </a>


        <div class="dashboard-section bg-success text-white">
            <i class="fas fa-book fa-3x"></i>
            <?php include 'total_user_borrowed.php'; ?>
        </div>

        <a href="userreturnhistory.php" class="dashboard-section bg-secondary text-white" style="text-decoration: none;">
            <i class="fas fa-reply-all fa-3x"></i>
            <?php include 'total_user_returned.php'; ?>
        </a>


        <div class="dashboard-section bg-warning text-white">
            <i class="fas fa-calendar-check fa-3x"></i>
            <?php include 'total_user_borrowed_today.php'; ?>
        </div>


        <a href="borrowedbooks.php" class="dashboard-section bg-danger text-white" style="text-decoration: none;">
            <i class="fas fa-exclamation-triangle fa-3x"></i>
            <?php include 'user_overdue_books.php'; ?>
        </a>




        <div class="dashboard-section bg-info text-white">
            <i class="fas fa-star fa-3x mr-3"></i>
            <h3 class="fw-bold" id="title">My Favorite Books</h3>
            <div id="userMostBorrowedBooks" style="display: none;">
                <?php include 'user_most_borrowed_books.php'; ?>
            </div>
            <button class="btn btn-sm btn-secondary" id="expandButton" onclick="toggleCollapse('userMostBorrowedBooks')">Expand</button>
            <script>
                function toggleCollapse(elementId) {
                    var element = document.getElementById(elementId);
                    var button = document.getElementById("expandButton");
                    var title = document.getElementById("title");
                    if (element.style.display === "none") {
                        element.style.display = "block";
                        button.textContent = "Collapse";
                        title.style.display = "none";
                    } else {
                        element.style.display = "none";
                        button.textContent = "Expand";
                        title.style.display = "block";
                    }
                }
            </script>
        </div>


        <a href="myprofile.php" class="dashboard-section bg-dark text-light" style="text-decoration: none;">
            <div class="text-center">
                <i class="fas fa-edit fa-2x mr-2"></i>
                <h2><i>Edit your profile!</i></h2>
            </div>
        </a>
    </div>



</body>

</html>