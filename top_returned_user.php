<?php
// Include database connection
include 'config.php';

// Query to get the top users who have returned the most books
$query_top_returned_users = "
    SELECT u.username, COUNT(rh.user_id) AS return_count
    FROM return_history rh
    JOIN users u ON rh.user_id = u.id
    GROUP BY rh.user_id
    ORDER BY return_count DESC
    LIMIT 10"; // Change the LIMIT value to adjust the number of top returned users you want to display

// Execute the query to get the top returned users
$result_top_returned_users = mysqli_query($conn, $query_top_returned_users);

// Check if the query was successful
if ($result_top_returned_users) {
    echo '<div style="display: flex; flex-direction: column; align-items: center;">';
    echo "<h3 style='margin-bottom: 20px; text-align: center; color: red;' class='fw-bold'>Top Returning Users</h3>"; // Apply red color to the heading
    echo "<ol style='list-style-type: decimal; padding-left: 20px;'>"; // Start an ordered list with decimal numbering
    
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result_top_returned_users)) {
        echo "<li style='margin-bottom: 5px; color: red; font-weight:bold; font-size: 18px;'>{$row['username']} (Returned {$row['return_count']} books)</li>"; // Include the counter for each user and apply red color
    }
    echo "</ol>"; // End the ordered list
    echo "</div>"; // End the flex container
} else {
    // Display an error message if the query fails
    echo "Error fetching top returned users: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
