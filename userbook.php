<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: login.php");
    exit;
}
?>

<?php
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
    <title>Books section</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="stylenav.css">
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fa-css/all.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        /* Fixed position for the header container */
        .header-container {
            margin-top: 85px;
            /* Ensure the header is above other content */
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

        label {
            font-weight: bold;
        }
        .wrapper1 {
            margin-top: 20px;
            display: flex;
            width: 100%;
            justify-content: start;
            flex-wrap: wrap;
            padding: 0 30px 0 30px;
        }

        .card1 {
            width: 280px;
            height: 420px;
            padding: 2rem 1rem;
            background: #fff;
            position: relative;
            box-shadow: 0px 7px 10px rgba(0, 0, 0, 0.5);
            transition: 0.5s ease-in-out;
            margin-bottom: 20px;
            margin-left: 35px;
            overflow: hidden;
        }

        .card1::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            width: 100%;
            height: 100%;
            z-index: 2;
            transition: 0.5s all;
            opacity: 0;

        }

        .card1:hover::before {
            opacity: 1;

        }

        .card1 img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            transition: transform 0.5s ease, filter 0.5s ease;
            /* Add transition for smooth animation */
        }

        .card1:hover img {
            transform: scale(1.1);
            /* Apply zoom effect on hover */
        }


        .card1 .info {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 3;
            color: #fff;
            opacity: 0;
            transition: 0.5s all;
            padding: 20px;
            box-sizing: border-box;
        }

        .card1:hover .info {
            opacity: 1;
            cursor: pointer;
        }

        .card1 .info .heading1 {
            margin: 0;
            text-overflow: ellipsis;
            text-align: center;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.9);
            color: blue;
            border-radius: 10px;
            padding: 5px;
        }
        .heading1 {
    font-family: 'Lora', serif;
    font-size: 20px; /* Adjust the font size */
    font-weight: bold; /* Make the title bold */
    color: #444; /* Set the color to a slightly darker shade */
    text-transform: uppercase; /* Convert text to uppercase */
    letter-spacing: 1px; /* Add spacing between letters */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Add a subtle shadow effect */
}

        .badge-lg {
            font-size: 1rem;
        }

        .btn {
            font-size: 15px;
        }
    </style>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title p-1">
                <img src="Images/logo.png" alt="" style="height:50px;">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="userbook.php"><i class="fa fa-book fa-lg"></i> Books</a>
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


    <div class="header-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-3 clearfix">
                        <h2 class="float-start">Books</h2>
                        <button class="btn btn-secondary btn-md float-end me-2" type="button" id="refreshButton" data-bs-toggle="tooltip" data-bs-title="Refresh">
                            <i class="fa fa-refresh"></i>
                        </button>

                        <button class="btn btn-dark text-light btn-md float-end me-2" type="button" id="searchButton" data-bs-toggle="tooltip" data-bs-title="Search">
                            <i class="fa fa-search"></i>
                        </button>
                        <input type="text" id="searchInput" class="form-control form-control-md float-end me-2" placeholder="Search Title" style="width:115px;" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="wrapper1">
    <?php
    // Include config file
    require_once "config.php";

    // Attempt select query execution
    $sql = "SELECT * FROM books ORDER BY book_id DESC";

    if ($result = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<div class="card1">';
                echo '<img src="' . $row['image_path'] . '" alt="Book Image">';
                echo '<div class="info">';
                echo '<div class="mt-auto">';
                // Display the title of the book
                echo '<div class="heading1 mb-2">' . $row['title'] . '</div>';
                // Display availability badge
                echo '  <span class="badge bg-' . (($row['availability'] == 'Available') ? 'success' : 'danger') . ' text-light mb-2 badge-lg">' . $row['availability'] . '</span>';

                // Display buttons
                echo '<div class="d-flex justify-content-center">';
                echo '<a href="userviewbook.php?book_id=' . $row['book_id'] . '" class="btn btn-primary me-2 fw-bold">Read More</a>';
                echo '<a href="borrow.php?book_id=' . $row['book_id'] . '" class="btn btn-warning fw-bold">Borrow Book</a>';
                echo '</div>'; // Close d-flex div
                echo '</div>'; // Close mt-auto div
                echo '</div>'; // Close info div
                echo '</div>'; // Close card1 div
            }
            // Free result set
            mysqli_free_result($result);
        } else {
            echo '<p>No books available</p>';
        }
    } else {
        echo '<p>Unable to fetch books</p>';
    }

    // Close connection
    mysqli_close($conn);
    ?>
</div>


    <div class="col no-results rounded-3 p-3" style="display: none;">
        <div class="alert alert-danger fw-bold text-danger" role="alert">No results found.</div>
    </div>






    <button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fa fa-arrow-up"></i></button>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    <script src="jquery/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchButton").click(function() {
                var searchText = $("#searchInput").val().trim().toLowerCase(); // Remove leading and trailing spaces
                var found = false; // Flag to track if any results are found
                $(".card1").each(function() {
                    var title = $(this).find(".heading1").text().toLowerCase(); // Change .card-title to .heading1

                    if (title.indexOf(searchText) === -1) {
                        $(this).hide(); // Hide the entire card1
                    } else {
                        $(this).show(); // Show the entire card1
                        found = true; // Set flag to true if at least one result is found
                    }
                });
                // Display message if no results are found
                if (!found) {
                    $(".no-results").show();
                } else {
                    $(".no-results").hide();
                }
            });

            // Refresh button click event
            $("#refreshButton").click(function() {
                location.reload(); // Reload the page
            });
        });
    </script>




    <script>
        $(document).ready(function() {
            // Show or hide the button based on scroll position
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#backToTopBtn').fadeIn();
                } else {
                    $('#backToTopBtn').fadeOut();
                }
            });

            // Scroll to top when button is clicked
            $('#backToTopBtn').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                return false;
            });
        });
    </script>
</body>

</html>