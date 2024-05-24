<style>
    .borrowers-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .borrowers-heading {
        margin-bottom: 10px;
        text-align: center;
        color: dark;
        font-weight: bold;
    }

    .borrowers-list {
        list-style-type: decimal;
        padding-left: 20px;
    }

    .borrower-item {
        margin-bottom: 5px;
        color: dark;
        font-weight: bold;
        font-size: 18px;
    }
</style>


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
    // Start the div with flex styles
    echo '<div class="borrowers-container">';
    // Heading with class for styling
    echo "<h3 class='borrowers-heading'>Top Borrowers</h3>";
    echo "<h6>(by borrow count)</h6>"; 
    // Start the ordered list with class for styling
    echo "<ol class='borrowers-list'>"; 
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        // List item with class for styling
        echo "<li class='borrower-item'>{$row['username']} - {$row['borrow_count']}</li>"; 
    }
    // Close the ordered list
    echo "</ol>";
    // Close the flex div
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
