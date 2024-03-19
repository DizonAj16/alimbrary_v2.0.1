<?php
// Include database connection
include 'config.php';

// Query to get the top N most borrowed books
$query = "
    SELECT b.title, COUNT(*) AS borrow_count
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.book_id
    GROUP BY bb.book_id
    ORDER BY borrow_count DESC
    LIMIT 10"; // Change the LIMIT value to adjust the number of top books you want to display

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    echo '<div class="text-center">';
    echo "<h3>Top Borrowed Books</h3>";
    echo "<ul style='list-style-type: none; padding-left: 0; display: flex; flex-direction: column; align-items: flex-start;'>";
    // Fetch the result as an associative array
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li style='margin-bottom: 10px;'>{$count}. {$row['title']} - Borrowed {$row['borrow_count']} times</li>";
        $count++;
    }
    echo "</ul>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
