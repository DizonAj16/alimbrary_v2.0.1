<?php
// Include database connection
include 'config.php';

// Query to get the top N users who have borrowed the most books
$query = "
    SELECT u.username, COUNT(*) AS borrow_count
    FROM borrowed_books bb
    JOIN users u ON bb.user_id = u.id
    GROUP BY bb.user_id
    ORDER BY borrow_count DESC
    LIMIT 10"; // Change the LIMIT value to adjust the number of top users you want to display

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    echo '<div class="text-center">';
    echo "<h3>Top Borrowing Users</h3>";
    // Start the inline styling directly within the PHP echo
    echo "<ul style='list-style-type: none; padding-left: 0; display: flex; flex-direction: column; align-items: flex-start;'>";
    // Fetch the result as an associative array
    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        // Add inline style to make list items display inline
        echo "<li style='margin-bottom: 10px;'>{$count}. {$row['username']} - Borrowed {$row['borrow_count']} times</li>";
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
