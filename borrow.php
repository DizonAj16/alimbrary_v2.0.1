<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Check if book_id is provided in the query string
if (!isset($_GET["book_id"])) {
    header("location: userbook.php");
    exit;
}

$book_id = $_GET["book_id"];

// Check if the book is available
$sql = "SELECT title, availability FROM books WHERE book_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $book_title, $availability); 
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// If the book is not available, display an alert message and redirect
if ($availability !== 'Available') {
    echo '<script>alert("The book is not available for borrowing."); window.location.href = "userbook.php";</script>';
    exit;
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate return date
    $return_date = trim($_POST["return_date"]);
    if (empty($return_date)) {
        $return_date_err = "Please enter return date.";
    }

    // Check if return date is in the future
    if (strtotime($return_date) <= strtotime(date("Y-m-d"))) {
        $return_date_err = "Return date must be in the future.";
    }

    // Check if there are no errors before inserting into database
    if (empty($return_date_err)) {
        // Begin a transaction
        mysqli_begin_transaction($conn);

        // Update availability status to 'Not Available'
        $update_sql = "UPDATE books SET availability = 'Not Available' WHERE book_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $book_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        // Insert borrowing record into borrowed_books table
        $borrow_date = date("Y-m-d H:i:s"); // Change to include time
        $insert_sql = "INSERT INTO borrowed_books (user_id, book_id, borrow_date, return_date) VALUES (?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "iiss", $_SESSION["id"], $book_id, $borrow_date, $return_date);
        mysqli_stmt_execute($insert_stmt);
        mysqli_stmt_close($insert_stmt);

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect to the books page
        echo "<script>alert('Book borrowed successfully...');</script>";
        echo '<script>
            setTimeout(function() {
                window.location.href = "borrowedbooks.php?prompt=success";
            }, 300); // Delay in milliseconds (2 seconds)
        </script>';
        exit;
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="fa-css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Montserrat, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            font-size: 24px;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            font-size: 18px;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            border-radius: 12px 12px 0 0;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            font-size: 20px;
            padding: 15px;
        }

        .modal-body,
        .modal-footer {
            padding: 20px;
        }

        .modal-footer {
            border-top: none;
        }

        .modal-footer .btn-primary,
        .modal-footer .btn-secondary {
            width: 100px;
        }

        .book-title {
            font-size: 22px;
            font-weight: bold;
            color: black;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        Borrow Book
                    </div>
                    <div class="card-body">
                        <h5 class="card-title book-title">Book title: <?php echo $book_title; ?></h5> 
                        <form id="borrowForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?book_id=' . $book_id; ?>" method="post">
                            <div class="form-group mb-2">
                                <label for="borrow_date" class="form-label mb-2">Borrow Date:</label>
                                <input type="text" id="borrow_date" name="borrow_date" class="form-control" value="<?php echo date('F j, Y g:i A'); ?>" readonly>

                            </div>
                            <div class="form-group mb-2">
                                <label for="return_date" class="form-label mb-2">Return Date:</label>
                                <input type="date" id="return_date" name="return_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Borrow</button>
                                <a href="userbook.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to borrow this book?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('borrowForm').submit();">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="jquery/jquery3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>

