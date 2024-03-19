<?php
// Include database connection
include 'config.php';

// Query to get the total count of available books
$query = "
    SELECT COUNT(*) AS total_available
    FROM books
    WHERE availability = 'available'";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Display the total count of available books
    echo '<div class="text-center">';
    echo "<h3>Total Available Books: {$row['total_available']} </h3>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
