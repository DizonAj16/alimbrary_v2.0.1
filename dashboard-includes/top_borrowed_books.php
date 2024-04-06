<?php
// Include database connection
include '../config.php';

// Query to get the top N most borrowed books
$query = "
    SELECT b.title, COUNT(*) AS borrow_count
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.book_id
    GROUP BY bb.book_id
    ORDER BY borrow_count DESC
    LIMIT 10"; 

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    echo '<div class="text-center">';
    echo "<h3 style='color: white; margin-bottom: 20px;' class='fw-bold'>Most Popular Books</h3>"; 
    echo "<div style='display: flex; flex-direction: column; align-items: center;'>"; 
    echo "<ol style='list-style-type: decimal; padding-left: 20px;'>"; 
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        // Display each book title and its borrow count
        echo "<li style='color: white; text-align: start; font-weight:bold; font-size:18px;'>{$row['title']} - Borrowed {$row['borrow_count']} times</li>"; // Apply red color to the list items and center the text
    }
    echo "</ol>";
    echo "</div>"; 
    echo "</div>"; 
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
