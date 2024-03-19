<?php
// Include database connection
include 'config.php';

// Query to get the total number of books currently borrowed by users
$query = "
    SELECT COUNT(*) AS total_borrowed_books
    FROM borrowed_books bb
    LEFT JOIN return_history rh ON bb.borrow_id = rh.borrow_id
    WHERE rh.borrow_id IS NULL";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Display the total number of books currently borrowed by users
    echo '<div class="text-center">';
    echo "<h3>Total number of books currently borrowed by users: {$row['total_borrowed_books']}</h3>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
