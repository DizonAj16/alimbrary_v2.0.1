<?php
// Include config file
include '../config.php';

// Check if user_id parameter is provided in the session
if (isset($_SESSION["id"]) && !empty(trim($_SESSION["id"]))) {
    // Prepare a select statement to count overdue or nearing due books
    $sql = "SELECT COUNT(*) AS total_overdue FROM borrowed_books 
            LEFT JOIN return_history ON borrowed_books.borrow_id = return_history.borrow_id
            WHERE borrowed_books.user_id = ? 
            AND (borrowed_books.return_date <= CURRENT_TIMESTAMP OR 
                 borrowed_books.return_date <= DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY)) 
            AND return_history.borrow_id IS NULL";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);

        // Set parameters
        $param_user_id = $_SESSION["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if there are any overdue or nearing due books
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $total_overdue);

                // Fetch result
                mysqli_stmt_fetch($stmt);

                // Display total overdue or nearing due books
                echo "<h3 class='fw-bold text-center'>" . $total_overdue . " Overdue or Nearing Due Books</h3>";
            } else {
                echo "<h3 class='fw-bold text-center' style='font-size: 30px;'>No overdue or nearing due books</h3>";
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
