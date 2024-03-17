<?php
    // Initialize the session
    session_start();

    // Check if the user is logged in, if not then redirect him to login page
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
        <title>Books section</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="navstyle.css">
        <link rel="stylesheet" href="titlestyle.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="fa-css/all.css">
        <script defer src="js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                font-family: 'Arial', sans-serif;
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

            .card:hover {
                background: linear-gradient(to bottom, #add8e6, #4682b4);
                color: white;
                cursor: pointer;
            }
        </style>


    </head>

    <body>
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
        <a class="nav-link" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
      </li>
      <li class="nav-item active">
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
                            <input type="text" id="searchInput" class="form-control form-control-md float-end me-2" placeholder="Search books" style="width:100px;" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>



        
        <div class="container-fluid mt-3">
            <div class="row">
                <?php
                // Include config file
                require_once "config.php";

                // Attempt select query execution
                $sql = "SELECT * FROM books";
                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
                            echo '<div class="card h-100 border border-primary rounded-5 shadow">';
                            echo '<div class="d-flex justify-content-center align-items-center mt-2" style="height: 200px;">';

                            // Display the image if image path exists
                            if (!empty($row['image_path'])) {
                                echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Book Image" style="max-height: 200px; max-width: 150px;">';
                            } else {
                                echo '<span class="text-center">No image available</span>';
                            }
                            echo '</div>';

                            echo '<div class="card-body">';
                            echo '<h5 class="card-title text-center mb-3 fw-bold" style="height: 50px; overflow: hidden; text-overflow: ellipsis; font-size: 18px;" title="' . $row['title'] . '">' . $row['title'] . '</h5>';
                            echo '<p class="card-text text-center mb-3" style="font-size: 16px;">';
                            echo '  <span class="badge bg-' . (($row['availability'] == 'Available') ? 'success' : 'danger') . ' text-light">' . $row['availability'] . '</span>';
                            echo '</p>';
                            echo '<div class="d-flex justify-content-center">';
                            echo '<a href="userviewbook.php?book_id=' . $row['book_id'] . '" class="btn btn-dark rounded-circle me-2" title="View Book" data-bs-toggle="tooltip"><i class="fas fa-book-open"></i></a>';
                            echo '<a href="borrow.php?book_id=' . $row['book_id'] . '" class="btn btn-info text-light rounded-circle me-2" title="Borrow Book" data-bs-toggle="tooltip"><span class="fa fas fa-hand-rock fa-lg"></span></a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="col">';
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col">';
                    echo '<div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div>';
                    echo '</div>';
                }

                // Close connection
                mysqli_close($conn);
                ?>

            </div>
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
                    $(".card").each(function() {
                        var title = $(this).find(".card-title").text().toLowerCase();
                        var author = $(this).find(".card-text").eq(0).text().toLowerCase();
                        var isbn = $(this).find(".card-text").eq(1).text().toLowerCase();
                        var pubYear = $(this).find(".card-text").eq(2).text().toLowerCase();
                        var genre = $(this).find(".card-text").eq(3).text().toLowerCase();
                        var availability = $(this).find(".badge").text().toLowerCase(); // Get availability text

                        if (title.indexOf(searchText) === -1 && author.indexOf(searchText) === -1 && isbn.indexOf(searchText) === -1 && pubYear.indexOf(searchText) === -1 && genre.indexOf(searchText) === -1 && availability.indexOf(searchText) === -1) {
                            $(this).parent('.col-lg-3').hide(); // Hide the entire column
                        } else {
                            $(this).parent('.col-lg-3').show(); // Show the entire column
                        }
                    });
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