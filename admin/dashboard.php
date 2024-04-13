<?php
// Start session
session_start();

// Check if the user is logged in and is a user, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: ../login.php");
    exit;
}
require_once '../config.php';
// Retrieve user's profile image
$user_id = $_SESSION["id"];
$sql = "SELECT image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $profile_image);
mysqli_stmt_fetch($stmt);
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
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 50px;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .dashboard-section:hover {
            cursor: pointer;
        }


        @media (max-width: 768px) {
            .dashboard-section {
                flex-basis: calc(100% - 40px);
            }
        }

        .dashboard-section i {
            margin-bottom: 10px;
        }


        .currently-borrowed-books.wider {
            width: 865px;
            max-width: none;
        }

        .gradient1 {
            background: linear-gradient(to bottom, rgba(255, 0, 0, 0), rgba(255, 0, 0, 0.6));
        }

        .gradient2 {
            background: linear-gradient(to bottom, rgba(0, 255, 0, 0), rgba(0, 255, 0, 0.6));
        }

        .gradient3 {
            background: linear-gradient(to bottom, rgba(0, 0, 255, 0), rgba(0, 0, 255, 0.6));
        }

        .gradient4 {
            background: linear-gradient(to bottom, rgba(255, 255, 0, 0), rgba(255, 255, 0, 0.6));
        }

        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            cursor: pointer;
            border-radius: 50%;
        }

        #backToTopBtn:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title p-1">
                <img src="../Images/logo.png" alt="" class="logo">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="users.php"><i class="fa fa-users fa-lg"></i> Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowhistory.php"><i class="fa fa-history fa-lg"></i> Borrow History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="returnhistory.php"><i class="fa fa-archive fa-lg"></i> Return History</a>
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


    <div class="dashboard-container" id="dashboard" style="padding-top: 90px;">

        <!-- Modify the dashboard sections with solid background colors -->
        <a href="adminbooks.php" class="dashboard-section bg-primary text-white" style="text-decoration: none;">
            <i class="fas fa-book fa-3x"></i>
            <div id="book-count">
                <?php include '../dashboard-includes/total_books.php'; ?>
            </div>
        </a>

        <a href="users.php" class="users-list dashboard-section bg-success text-white" style="text-decoration: none;">
            <i class="fas fa-users fa-3x"></i>
            <?php include '../dashboard-includes/get_users.php'; ?>
        </a>

        <a href="../dashboard-includes/not_availablebooks.php" class="current_borrowing_users dashboard-section bg-warning text-white" style="text-decoration: none;">
            <i class="fas fa-user-clock fa-3x"></i>
            <?php include '../dashboard-includes/current_borrowing_users.php'; ?>
        </a>

        <a href="../dashboard-includes/available_books.php" class="total-available-books dashboard-section bg-info text-white" style="text-decoration: none;">
            <i class="fas fa-book-open fa-3x"></i>
            <?php include '../dashboard-includes/total_available_books.php'; ?>
        </a>

        <div class="total-borrowed-and-returned dashboard-section bg-primary text-white">
            <i class="fas fa-exchange-alt fa-3x"></i>
            <?php include '../dashboard-includes/total_borrowed_and_returned.php'; ?>
        </div>

        <div class="top-user-borrowed dashboard-section bg-success text-white" id="topUserBorrowedSection">
            <i class="fas fa-chart-bar fa-3x"></i>
            <h3 class="fw-bold" id="topUserBorrowedTitle">Top Borrowers</h3>
            <div id="topUserBorrowedContent" style="display: none;">
                <?php include '../dashboard-includes/top_user_borrowed.php'; ?>
            </div>
            <button class="btn btn-transparent btn-md fw-bold text-light" onclick="toggleExpand('topUserBorrowed')"><i class="fas fa-chevron-down fa-lg"></i> Click to expand</button>
        </div>

        <div class="top-returned-user dashboard-section bg-danger text-white" id="topReturnedUserSection">
            <i class="fas fa-chart-line fa-3x"></i>
            <h3 class="fw-bold" id="topReturnedUserTitle">Top Returners</h3>
            <div id="topReturnedUserContent" style="display: none;">
                <?php include '../dashboard-includes/top_returned_user.php'; ?>
            </div>
            <button class="btn btn-transparent btn-md fw-bold text-light" onclick="toggleExpand('topReturnedUser')"><i class="fas fa-chevron-down fa-lg"></i> Click to expand</button>
        </div>

        <div class="most-borrowed-books dashboard-section bg-warning text-white" id="mostBorrowedBooksSection">
            <i class="fas fa-star fa-3x"></i>
            <h3 class="fw-bold text-center" id="mostBorrowedBooksTitle">Most Popular Books</h3>
            <div id="mostBorrowedBooksContent" style="display: none;">
                <?php include '../dashboard-includes/top_borrowed_books.php'; ?>
            </div>
            <button class="btn btn-transparent btn-md fw-bold text-light" onclick="toggleExpand('mostBorrowedBooks')"><i class="fas fa-chevron-down fa-lg"></i> Click to expand</button>
        </div>

        <div class="top-returned-books dashboard-section bg-info text-white" id="topReturnedBooksSection">
            <i class="fas fa-book-reader fa-3x"></i>
            <h3 class="fw-bold" id="topReturnedBooksTitle">Most Returned Books</h3>
            <div id="topReturnedBooksContent" style="display: none;">
                <?php include '../dashboard-includes/top_returned_books.php'; ?>
            </div>
            <button class="btn btn-transparent btn-md fw-bold text-light" onclick="toggleExpand('topReturnedBooks')"><i class="fas fa-chevron-down fa-lg"></i> Click to expand</button>
        </div>


    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var contentSections = document.querySelectorAll(".dashboard-section > div[id$='Content']");
            contentSections.forEach(function(section) {
                section.style.display = "none";
            });
        });

        function toggleExpand(sectionId) {
            var content = document.getElementById(sectionId + "Content");
            var title = document.getElementById(sectionId + "Title");
            var button = document.querySelector(`button[onclick="toggleExpand('${sectionId}')"]`);

            if (content.style.display === "none") {
                content.style.display = "block";
                title.style.display = "none";
                button.innerHTML = '<i class="fas fa-chevron-up fa-lg"></i> Click to collapse';
            } else {
                content.style.display = "none";
                title.style.display = "block";
                button.innerHTML = '<i class="fas fa-chevron-down fa-lg"></i> Click to expand';
            }
        }
    </script>

    </div>

    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fas fa-arrow-up"></i></button>
    <script src="../jquery/jquery-3.5.1.min.js"></script>
    <script src="../scripts/backtotop.js?<?php echo time(); ?>"></script>



</body>

</html>