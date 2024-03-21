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
    echo '<div style="display: flex; flex-direction: column; align-items: center;">';
    echo "<h3 style='margin-bottom: 20px; text-align: center; color: red;' class='fw-bold'>Top Borrowers</h3>"; // Apply red color to the heading and add margin-bottom for spacing
    // Start the inline styling directly within the PHP echo
    echo "<ol style='list-style-type: decimal; padding-left: 20px;'>"; // Changed to <ol> for ordered list
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        // Add inline style to make list items display inline and apply red color
        echo "<li style='margin-bottom: 5px; color: red; font-weight:bold; font-size: 18px;'>{$row['username']} - Borrowed {$row['borrow_count']} times</li>"; // Added font-size property
    }
    echo "</ol>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
