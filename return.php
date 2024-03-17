<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Confirmation</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-top: 50px;
        }

        h2 {
            color: black;
        }

        p {
            margin-bottom: 20px;
        }

        form {
            display: flex;
            gap: 10px;
        }

        .btn-success, .btn-secondary {
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Return Confirmation</h2>
        <p>Are you sure you want to return this book?</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> <!-- Changed method to POST -->
            <input type="hidden" name="borrow_id" value="<?php echo isset($_GET['borrow_id']) ? $_GET['borrow_id'] : ''; ?>">
            <button type="submit" name="confirm_return" class="btn btn-success">Confirm</button>
            <a href="borrowedbooks.php" class="btn btn-secondary">Cancel</a>
        </form>

        <?php
        // Start session
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: login.php");
            exit;
        }

        // Include config file
        require_once "config.php";

        // Check if confirm_return is set in the POST data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["confirm_return"])) {
            // Check if borrow_id is set in the POST data
            if (isset($_POST["borrow_id"]) && !empty(trim($_POST["borrow_id"]))) {
                // Prepare a SQL statement to insert return information into return_history table
                $insert_return_sql = "INSERT INTO return_history (user_id, book_id, borrow_id, returned_date_time, status) VALUES (?, (SELECT book_id FROM borrowed_books WHERE borrow_id = ?), ?, CURRENT_TIMESTAMP, 'returned')";

                if ($stmt = mysqli_prepare($conn, $insert_return_sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "iii", $param_user_id, $param_borrow_id, $param_borrow_id);

                    // Set parameters
                    $param_user_id = $_SESSION["id"];
                    $param_borrow_id = trim($_POST["borrow_id"]);

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Prepare a SQL statement to update the availability of the book to "Available"
                        $update_book_sql = "UPDATE books SET availability = 'Available' WHERE book_id IN (SELECT book_id FROM borrowed_books WHERE borrow_id = ?)";

                        if ($stmt2 = mysqli_prepare($conn, $update_book_sql)) {
                            // Bind variables to the prepared statement as parameters
                            mysqli_stmt_bind_param($stmt2, "i", $param_borrow_id);

                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt2)) {
                                // Redirect to the borrowed books page
                                header("location: borrowedbooks.php?prompt=success");
                                exit();
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }

                            // Close statement
                            mysqli_stmt_close($stmt2);
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            } else {
                echo "Invalid borrow_id.";
            }
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>
