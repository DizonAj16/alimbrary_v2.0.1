<?php
// Include database connection
include '../config.php';

// Query to get the top N users who have borrowed the most books
$query = "
    SELECT u.username, COUNT(*) AS borrow_count
    FROM borrowed_books bb
    JOIN users u ON bb.user_id = u.id
    GROUP BY bb.user_id
    ORDER BY borrow_count DESC
    LIMIT 10"; 

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    echo '<div style="display: flex; flex-direction: column; align-items: center;">';
    echo "<h3 style='margin-bottom: 20px; text-align: center; color: white;' class='fw-bold'>Top Borrowers</h3>"; 
    // Start the inline styling directly within the PHP echo
    echo "<ol style='list-style-type: decimal; padding-left: 20px;'>"; 
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li style='margin-bottom: 5px; color: white; font-weight:bold; font-size: 18px;'>{$row['username']} - Borrowed {$row['borrow_count']} times</li>"; 
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
