<?php
// Include config file
include '../config.php';

// Check if user_id parameter is provided in the session
if (isset($_SESSION["id"]) && !empty(trim($_SESSION["id"]))) {
    // Prepare a select statement to get the 5 most borrowed books by the user
    $sql = "SELECT book_id, COUNT(*) AS borrow_count FROM borrowed_books WHERE user_id = ? GROUP BY book_id ORDER BY borrow_count DESC LIMIT 10";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);

        // Set parameters
        $param_user_id = $_SESSION["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if there are any borrowed books
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $book_id, $borrow_count);

                // Display user's most borrowed books
                echo "<h3 class='fw-bold text-center'>My Favorite Books</h3>";
                echo "<h6 class='text-center'>(by borrow count)</h6>";
                echo "<ol>";
                while (mysqli_stmt_fetch($stmt)) {
                    // Fetch book details using book_id and display them
                    $book_sql = "SELECT title FROM books WHERE book_id = ?";
                    if ($book_stmt = mysqli_prepare($conn, $book_sql)) {
                        mysqli_stmt_bind_param($book_stmt, "i", $book_id);
                        mysqli_stmt_execute($book_stmt);
                        mysqli_stmt_bind_result($book_stmt, $title);
                        mysqli_stmt_fetch($book_stmt);
                        echo "<li class='fw-bold'>$title - $borrow_count</li>";
                        mysqli_stmt_close($book_stmt);
                    }
                }
                echo "</ol>";
            } else {
                echo "<h3 class='fw-bold'>No books borrowed yet.</h3>";
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
