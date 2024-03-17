    <?php
    // Initialize the session
    session_start();

    // Check if the user is logged in, if not then redirect him to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
        header("location: login.php");
        exit;
    }
    ?>

    <?php
    // Include config file
    require_once "config.php";

    // Initialize variables
    $title = $author = $isbn = $pub_year = $genre = $availability = $description = "";
    $title_err = $author_err = $isbn_err = $pub_year_err = $genre_err = $image_err = $description_err = "";

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and retrieve form data
        $title = trim($_POST["title"]);
        $description = trim($_POST["description"]);
        $author = trim($_POST["author"]);
        $isbn = trim($_POST["isbn"]);
        $pub_year = trim($_POST["pub_year"]);
        $genre = trim($_POST["genre"]);
        $availability = $_POST["availability"] ?? "";

        // Generate and check ISBN
        do {
            // Generate a random number for the first part of the ISBN (9 digits)
            $random_number = mt_rand(100000000, 999999999);

            // Format the random number to match ISBN format (###-##########)
            $isbn = "978-" . $random_number;

            // Check if the generated ISBN already exists in the database
            $sql_check_isbn = "SELECT * FROM books WHERE isbn = ?";
            $stmt_check_isbn = mysqli_prepare($conn, $sql_check_isbn);
            mysqli_stmt_bind_param($stmt_check_isbn, "s", $isbn);
            mysqli_stmt_execute($stmt_check_isbn);
            $result_check_isbn = mysqli_stmt_get_result($stmt_check_isbn);
            $num_rows = mysqli_num_rows($result_check_isbn);
        } while ($num_rows > 0);

        // Validate title, author, isbn, publication year, and genre (you already have this code)

        // Check if file is uploaded without errors
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
            // Process and move the uploaded file
            $target_dir = "uploads/"; // Directory where uploaded files will be stored
            $target_file = $target_dir . basename($_FILES["image"]["name"]); // Path of the uploaded file
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // File extension

            // Check if the file is an actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // File uploaded successfully, now insert book data into the database
                        $image_path = $target_file; // Get image path
                    } else {
                        $image_err = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $image_err = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                }
            } else {
                $image_err = "File is not an image.";
            }
        } else {
            $image_err = "No image file uploaded.";
        }

        // Free the result set and close the statement
        mysqli_free_result($result_check_isbn);
        mysqli_stmt_close($stmt_check_isbn);

        // Check input errors before inserting into database
        if (empty($title_err) && empty($author_err) && empty($isbn_err) && empty($pub_year_err) && empty($genre_err) && empty($image_err) && empty($description_err)) {
            // Check if any of the required fields are empty
            if (empty($title) || empty($author) || empty($isbn) || empty($pub_year) || empty($genre) || empty($image_path) || empty($description)) {
                // Redirect to landing page or any other appropriate action
                header("location: adminbooks.php");
                exit();
            }

            // Prepare an insert statement
            $sql = "INSERT INTO books (title, author, description, isbn, pub_year, genre, image_path, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssssssss", $param_title, $param_author, $param_description, $param_isbn, $param_pub_year, $param_genre, $param_image_path, $param_availability);

                // Set parameters
                $param_title = $title;
                $param_author = $author;
                $param_description = $description;
                $param_isbn = $isbn;
                $param_pub_year = $pub_year;
                $param_genre = $genre;
                $param_image_path = $image_path;
                $param_availability = $availability;


                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Book created successfully...');</script>";
                    echo '<script>
                    setTimeout(function() {
                    window.location.href = "adminbooks.php";
                        }, 1000); // Delay in milliseconds (2 seconds)
        </script>';
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }


            // Close statement
            mysqli_stmt_close($stmt);
        }
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
                            <a class="nav-link" href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="users.php"><i class="fa fa-user-circle fa-lg"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="borrowhistory.php"><i class="fa fa-users fa-lg"></i> Borrow History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="returnhistory.php"><i class="fa fa-address-book fa-lg"></i> Return History</a>
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
                            <button type="button" class="btn btn-success btn-md float-end me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" title="Add New Book">
                                <i class="fa fa-plus-circle text-light"></i>
                            </button>

                            <button class="btn btn-secondary btn-md float-end me-2" type="button" id="refreshButton" data-bs-toggle="tooltip" data-bs-title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </button>

                            <button class="btn btn-dark text-light btn-md float-end me-2" type="button" id="searchButton" data-bs-toggle="tooltip" data-bs-title="Search">
                                <i class="fa fa-search"></i>
                            </button>
                            <input type="text" id="searchInput" class="form-control form-control-md float-end me-2" placeholder="Search Title" style="width:100px;" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="modal fade" id="exampleModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center"><i class="fa fa-book me-2"></i> Add Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                                        <div class="invalid-feedback"><?php echo $title_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $author; ?>">
                                        <div class="invalid-feedback"><?php echo $author_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                                        <div class="invalid-feedback"><?php echo $description_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" name="isbn" class="form-control <?php echo (!empty($isbn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $isbn; ?>">
                                        <div class="invalid-feedback"><?php echo $isbn_err; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pub_year" class="form-label">Year published</label>
                                        <input type="text" name="pub_year" class="form-control <?php echo (!empty($pub_year_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pub_year; ?>">
                                        <div class="invalid-feedback"><?php echo $pub_year_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="genre" class="form-label">Genre</label>
                                        <input type="text" name="genre" class="form-control <?php echo (!empty($genre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $genre; ?>">
                                        <div class="invalid-feedback"><?php echo $genre_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="availability" class="form-label">Availability</label>
                                        <select name="availability" class="form-control <?php echo (!empty($availability_err)) ? 'is-invalid' : ''; ?>">
                                            <option value="Available">Available</option>
                                            <option value="Not Available">Not Available</option>
                                        </select>
                                        <div class="invalid-feedback"><?php echo $availability_err; ?></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                                        <div class="invalid-feedback"><?php echo $image_err; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deleteModalLabel">Confirm Deletion</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this book?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
                $sql = "SELECT * FROM books
                        ORDER BY book_id DESC
                ";
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
                            echo '<a href="adminviewbook.php?book_id=' . $row['book_id'] . '" class="btn btn-dark rounded-circle me-2" title="View Book" data-bs-toggle="tooltip"><i class="fas fa-book-open"></i></a>';
                            echo '<a href="updatebook.php?book_id=' . $row['book_id'] . '" class="btn btn-info text-light rounded-circle me-2" title="Update Book" data-bs-toggle="tooltip"><span class="fa fa-pencil fa-lg"></span></a>';
                            echo '<a href="#" class="btn btn-danger rounded-circle delete-btn" data-book-id="' . $row['book_id'] . '" title="Delete Book" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash-alt"></i>
                            </a>';
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
                    $(".card").each(function() {
                        var title = $(this).find(".card-title").text().toLowerCase();

                        if (title.indexOf(searchText) === -1) {
                            $(this).parent('.col-lg-3').hide(); // Hide the entire column
                        } else {
                            $(this).parent('.col-lg-3').show(); // Show the entire column
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

        <script>
            $(document).ready(function() {
                // Store the book ID to delete when delete button is clicked
                var bookIdToDelete;

                // Handle delete button click
                $(".delete-btn").click(function() {
                    bookIdToDelete = $(this).data("book-id");
                });

                // Handle confirm deletion button click
                $("#confirmDeleteBtn").click(function() {
                    // AJAX request to delete the book
                    $.ajax({
                        url: "deletebook.php",
                        type: "POST",
                        data: {
                            book_id: bookIdToDelete
                        },
                        success: function(data) {
                            // Reload the page after successful deletion
                            location.reload();
                            alert('Book Deleted Successfully...')
                        },
                        error: function() {
                            alert("Error deleting book.");
                        }
                    });
                });
            });
        </script>


    </body>

    </html>