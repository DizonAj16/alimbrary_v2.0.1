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
$book_id = $title = $author = $isbn = $pub_year = $genre = $image_path = $availability = $description = "";

// Check if book_id parameter is provided in the URL
if (isset($_GET["book_id"]) && !empty(trim($_GET["book_id"]))) {
    // Prepare a select statement
    $sql = "SELECT * FROM books WHERE book_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_book_id);

        // Set parameters
        $param_book_id = trim($_GET["book_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field values
                $title = $row["title"];
                $author = $row["author"];
                $isbn = $row["isbn"];
                $pub_year = $row["pub_year"];
                $genre = $row["genre"];
                $image_path = $row["image_path"];
                $availability = $row["availability"];
                $description = $row["description"];
            } else {
                // URL doesn't contain a valid book_id parameter, redirect to the error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // URL doesn't contain a book_id parameter, redirect to the error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fa-css/all.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
        }

        .card {
            max-width: 90%;

        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .book-image {
            max-width: 100%;
            height: 450px;
            /* Increase image height */
            margin-bottom: 20px;
            border-radius: 10px;
            object-fit: cover;
        }

        .table th{
            border: none;
            padding: 10px;
            text-align: left;
            font-size: 18px;
        }
        .table td{
            border: none;
            padding: 10px;
            text-align: left;
            font-size: 18px;
        }
        .card-footer {
            text-align: center;
        }

        .btn-back {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .description {
            padding-top: 20px;
            font-size: 16px;
        }

        .book-title {
            font-family: 'Arial Black', sans-serif;
            color: #4CAF50;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
    <script src="js/bootstrap.bundle.js"></script>
</head>

<body>
    <div class="container">
        <div class="card mb-5">
            <div class="card-header">
                <h2 class="card-title">Book Details</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <?php if (!empty($image_path)) : ?>
                            <img src="<?php echo $image_path; ?>" class="book-image img-fluid" alt="Book Image">
                        <?php else : ?>
                            <span>No image available</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Title</th>
                                        <td class="book-title"><?php echo htmlspecialchars($title); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Author</th>
                                        <td><?php echo htmlspecialchars($author); ?></td>

                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td class="description"><?php echo htmlspecialchars($description); ?></td>
                                    </tr>
                                    <tr>
                                        <th>ISBN</th>
                                        <td><?php echo htmlspecialchars($isbn); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Year published</th>
                                        <td><?php echo htmlspecialchars($pub_year); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Genre</th>
                                        <td><?php echo htmlspecialchars($genre); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Availability</th>
                                        <td><?php echo htmlspecialchars($availability); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="adminbooks.php" class="text-light">
                    <button class="btn btn-back btn-md" data-bs-toggle="tooltip" data-bs-placement="top" title="Back to Books">
                        <i class="fas fa-arrow-left"></i></button>
                </a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>

</html>