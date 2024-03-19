<?php
// Include database connection
include 'config.php';

// Query to count the number of books
$query = "SELECT COUNT(*) AS book_count FROM books";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Display the number of books
    echo '<div class="text-center">';
    echo "<h3>Total number of books: " . $row['book_count'] . "</h3>";
    echo '</div>';
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
