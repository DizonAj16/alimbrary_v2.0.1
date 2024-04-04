<?php
// Include config file
include 'config.php';

// Check if user_id parameter is provided in the session
if (isset($_SESSION["id"]) && !empty(trim($_SESSION["id"]))) {
    // Prepare a select statement
    $sql = "SELECT COUNT(*) AS total_returned FROM return_history WHERE user_id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);

        // Set parameters
        $param_user_id = $_SESSION["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if there are any returned books
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $total_returned);

                // Fetch result
                mysqli_stmt_fetch($stmt);

                // Display total returned books
                echo "<h3 class='text-center fw-bold'>" . $total_returned . " Returned Books</h3>";
            } else {
                echo "<p>No books returned yet.</p>";
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
