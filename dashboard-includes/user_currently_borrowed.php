<?php
// Include config file
include '../config.php';

// Check if user_id parameter is provided in the session
if (isset($_SESSION["id"]) && !empty(trim($_SESSION["id"]))) {
    // Prepare a select statement to count the currently borrowed books by the user
    $sql = "SELECT COUNT(*) AS num_borrowed_books
            FROM borrowed_books 
            LEFT JOIN return_history ON borrowed_books.borrow_id = return_history.borrow_id 
            WHERE borrowed_books.user_id = ? AND return_history.borrow_id IS NULL";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);

        // Set parameters
        $param_user_id = $_SESSION["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if there are any currently borrowed books
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $num_borrowed_books);

                // Fetch the count of borrowed books
                mysqli_stmt_fetch($stmt);

                // Display the number of currently borrowed books
                echo "<h3 class='fw-bold text-center'>". $num_borrowed_books ." Currently Borrowed Books</h3>";
            } else {
                echo "<p>No books currently borrowed.</p>";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo "User ID is not provided.";
}
?>
