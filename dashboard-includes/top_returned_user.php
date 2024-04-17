<style>
    .returners-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .returners-heading {
        margin-bottom: 20px;
        text-align: center;
        color: dark;
        font-weight: bold;
    }

    .returners-list {
        list-style-type: decimal;
        padding-left: 20px;
    }

    .returner-item {
        margin-bottom: 5px;
        color: dark;
        font-weight: bold;
        font-size: 18px;
    }
</style>


<?php
// Include database connection
include '../config.php';

// Query to get the top users who have returned the most books
$query_top_returned_users = "
    SELECT u.username, COUNT(rh.user_id) AS return_count
    FROM return_history rh
    JOIN users u ON rh.user_id = u.id
    GROUP BY rh.user_id
    ORDER BY return_count DESC
    LIMIT 10"; 

// Execute the query to get the top returned users
$result_top_returned_users = mysqli_query($conn, $query_top_returned_users);

// Check if the query was successful
if ($result_top_returned_users) {
    // Start the div with flex styles
    echo '<div class="returners-container">';
    // Heading with class for styling
    echo "<h3 class='returners-heading'>Top Returners</h3>"; 
    // Start the ordered list with class for styling
    echo "<ol class='returners-list'>"; 
    
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result_top_returned_users)) {
        // List item with class for styling
        echo "<li class='returner-item'>{$row['username']} (Returned {$row['return_count']} books)</li>"; 
    }
    // Close the ordered list
    echo "</ol>"; 
    // Close the flex div
    echo "</div>"; 
} else {
    // Display an error message if the query fails
    echo "Error fetching top returned users: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
