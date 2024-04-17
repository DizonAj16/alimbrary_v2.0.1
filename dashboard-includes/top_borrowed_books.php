<style>
    .text-center {
        text-align: center;
    }

    .popular-books-heading {
        color: dark;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .popular-books-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .popular-books-list {
        list-style-type: decimal;
        padding-left: 20px;
    }

    .popular-book-item {
        color: dark;
        text-align: start;
        font-weight: bold;
        font-size: 18px;
    }
</style>


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
    // Start the div with flex styles
    echo '<div class="text-center">';
    // Heading with class for styling
    echo "<h3 class='popular-books-heading'>Most Popular Books</h3>"; 
    // Start the div with flex styles
    echo "<div class='popular-books-container'>"; 
    // Start the ordered list with class for styling
    echo "<ol class='popular-books-list'>"; 
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        // List item with class for styling
        echo "<li class='popular-book-item'>{$row['title']} - Borrowed {$row['borrow_count']} times</li>"; 
    }
    // Close the ordered list
    echo "</ol>";
    // Close the div with flex styles
    echo "</div>"; 
    // Close the div with text center alignment
    echo "</div>"; 
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
