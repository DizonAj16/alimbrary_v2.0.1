<?php
// Include database connection
include 'config.php';

// Query to get the top 5 returned books
$query_top_returned = "
    SELECT b.title, COUNT(rh.book_id) AS return_count
    FROM return_history rh
    JOIN books b ON rh.book_id = b.book_id
    GROUP BY rh.book_id
    ORDER BY return_count DESC
    LIMIT 10"; 
// Execute the query to get the top 5 returned books
$result_top_returned = mysqli_query($conn, $query_top_returned);

// Check if the query was successful
if ($result_top_returned) {
    echo "<h3 style='margin-bottom: 20px; font-size: 30px;' class='text-center fw-bold'>Most Returned Books</h3>"; 
    echo '<div class="d-flex justify-content-center align-items-center">';
    echo "<div class='text-start'>";
    
    echo "<ol style='list-style-type: decimal; padding-left: 20px;'>"; // Use <ol> for numbered list
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result_top_returned)) {
        echo "<li style='font-weight:bold; font-size: 18px;'>{$row['title']} (Returned {$row['return_count']} times)</li>"; 
    }
    echo "</ol>";
    echo "</div>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error fetching top returned books: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
