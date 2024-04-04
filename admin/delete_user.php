<?php
// Include config file
require_once "config.php";

// Check if user ID parameter is set
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Define the user ID
    $user_id = trim($_GET["id"]);

    // Prepare a delete statement for associated records in return_history table
    $sql_delete_return_history = "DELETE FROM return_history WHERE borrow_id IN (SELECT borrow_id FROM borrowed_books WHERE user_id = ?)";

    // Prepare and execute the statement
    if ($stmt_delete_return_history = mysqli_prepare($conn, $sql_delete_return_history)) {
        mysqli_stmt_bind_param($stmt_delete_return_history, "i", $user_id);
        if (!mysqli_stmt_execute($stmt_delete_return_history)) {
            echo "Oops! Something went wrong while deleting associated records from return history.";
            exit();
        }
        mysqli_stmt_close($stmt_delete_return_history);
    }

    // Prepare a delete statement for associated records in borrowed_books table
    $sql_delete_borrowed_books = "DELETE FROM borrowed_books WHERE user_id = ?";

    // Prepare and execute the statement
    if ($stmt_delete_borrowed_books = mysqli_prepare($conn, $sql_delete_borrowed_books)) {
        mysqli_stmt_bind_param($stmt_delete_borrowed_books, "i", $user_id);
        if (!mysqli_stmt_execute($stmt_delete_borrowed_books)) {
            echo "Oops! Something went wrong while deleting associated records from borrowed books.";
            exit();
        }
        mysqli_stmt_close($stmt_delete_borrowed_books);
    }

    // Prepare a delete statement for the user
    $sql_delete_user = "DELETE FROM users WHERE id = ?";

    // Prepare and execute the statement
    if ($stmt_delete_user = mysqli_prepare($conn, $sql_delete_user)) {
        mysqli_stmt_bind_param($stmt_delete_user, "i", $user_id);
        if (mysqli_stmt_execute($stmt_delete_user)) {
            // Redirect to users page with success message after 1 second
            echo "<script>alert('User successfully deleted.'); setTimeout(function(){ window.location='users.php'; }, 1000);</script>";
            exit();
        } else {
            echo "Oops! Something went wrong while deleting the user.";
        }
        mysqli_stmt_close($stmt_delete_user);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // If user ID parameter is not set, redirect to error page
    header("location: error.php");
    exit();
}
?>
