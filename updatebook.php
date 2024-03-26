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
$title = $author = $isbn = $pub_year = $genre = $description = "";
$title_err = $author_err = $isbn_err = $pub_year_err = $genre_err = $image_err = $description_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and retrieve form data
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $isbn = trim($_POST["isbn"]);
    $pub_year = trim($_POST["pub_year"]);
    $genre = trim($_POST["genre"]);
    $description = trim($_POST["description"]);

    // Validate title, author, isbn, publication year, genre

    // Check if file is uploaded without errors (if an image is provided)
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Allow only certain file formats
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
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
        // No new image file uploaded, set image_path to null or retain the existing image_path if available
        if (!empty($_POST["current_image_path"])) {
            $image_path = $_POST["current_image_path"];
        }
    }

    // Check input errors before updating the database
    if (empty($title_err) && empty($author_err) && empty($isbn_err) && empty($pub_year_err) && empty($genre_err) && empty($image_err) && empty($description_err)) {
        // Prepare an update statement
        $sql = "UPDATE books SET title=?, author=?, isbn=?, pub_year=?, genre=?, description=?, image_path=? WHERE book_id=?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssi", $param_title, $param_author, $param_isbn, $param_pub_year, $param_genre, $param_description, $param_image_path, $param_book_id);

            // Set parameters
            $param_title = $title;
            $param_author = $author;
            $param_isbn = $isbn;
            $param_pub_year = $pub_year;
            $param_genre = $genre;
            $param_description = $description;
            $param_image_path = $image_path;
            $param_book_id = $_POST["book_id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Book updated successfully...');</script>";
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "adminbooks.php";
                    }, 300); // Delay in milliseconds (2 seconds)
                </script>';
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Check existence of book_id parameter before processing further
    if (isset($_GET["book_id"]) && !empty(trim($_GET["book_id"]))) {
        // Get URL parameter
        $book_id =  trim($_GET["book_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM books WHERE book_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_book_id);

            // Set parameters
            $param_book_id = $book_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $title = $row["title"];
                    $author = $row["author"];
                    $isbn = $row["isbn"];
                    $pub_year = $row["pub_year"];
                    $genre = $row["genre"];
                    $description = $row["description"];
                    $image_path = $row["image_path"];
                } else {
                    // URL doesn't contain a valid book_id parameter. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($conn);
    } else {
        // URL doesn't contain a book_id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fa-css/all.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 800px;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        h2 {
            color: #333;
            text-align: center;
            font-weight: bold;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-success,
        .btn-secondary {
            border-radius: 5px;
        }

        .btn-success {
            border: none;
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-secondary {
            border: none;
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .text-danger {
            color: #dc3545;
        }

        .image-preview {
            max-width: 200px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="text-light mt-2 mb-2">Update Book</h2>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo $image_path; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control" value="<?php echo $title; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="author">Author</label>
                                <input type="text" id="author" name="author" class="form-control" value="<?php echo $author; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="isbn">ISBN</label>
                                <input type="text" id="isbn" name="isbn" class="form-control" value="<?php echo $isbn; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="pub_year">Year published</label>
                                <input type="text" id="pub_year" name="pub_year" class="form-control" value="<?php echo $pub_year; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="genre">Genre</label>
                                <input type="text" id="genre" name="genre" class="form-control" value="<?php echo $genre; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control"><?php echo $description; ?></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label for="current_image">Current Image</label>
                                <?php if (!empty($image_path)) : ?>
                                    <br>
                                    <img src="<?php echo $image_path; ?>" alt="Current Image" class="img-fluid image-preview">
                                <?php else : ?>
                                    <span>No image available</span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="new_image" class="form-label">New Image</label>
                                <div class="input-group">
                                    <input type="file" id="new_image" name="image" class="form-control d-none" onchange="updateFileName(this)">
                                    <label for="new_image" class="input-group-text bg-success text-light fw-bold fs-4 rounded me-2">
                                        <i class="fas fa-upload"></i>
                                    </label>
                                    <input type="text" id="file_name" class="form-control" readonly>
                                </div>
                                
                                <span class="text-danger"><?php echo $image_err; ?></span>

                                <script>
                                    function updateFileName(input) {
                                        var fileName = input.files[0].name;
                                        document.getElementById("file_name").value = fileName;
                                    }
                                </script>

                            </div>


                        </div>
                    </div>
                    <div class="form-group text-center mt-3">
                        <input type="submit" class="btn btn-success" value="Update">
                        <a href="adminbooks.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script defer src="js/bootstrap.bundle.js"></script>
</body>

</html>