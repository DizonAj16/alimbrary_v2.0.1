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
    LIMIT 10"; // Change the LIMIT value to adjust the number of top returned books you want to display

// Execute the query to get the top 5 returned books
$result_top_returned = mysqli_query($conn, $query_top_returned);

// Check if the query was successful
if ($result_top_returned) {
    echo '<div>';
    echo "<h3 class='text-center' style='margin-bottom: 10px;'>Top Returned Books</h3>";
    echo "<ol style='padding-left: 20px;'>"; // Use <ol> for numbered list
    $count = 1; // Initialize numbering counter
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result_top_returned)) {
        if ($count == 1) { // Keep the first item centered
            echo "<div><li>{$row['title']} (Returned {$row['return_count']} times)</li></div>";
        } else { // Flex-start the rest of the items
            echo "<li>{$row['title']} (Returned {$row['return_count']} times)</li>";
        }
        $count++; // Increment counter for each item
    }
    echo "</ol>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error fetching top returned books: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
