<?php
// Include database connection
include 'config.php';

// Query to get all currently borrowed books
$query_borrowed = "
    SELECT b.title, u.username, bb.borrow_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.book_id
    JOIN users u ON bb.user_id = u.id
    LEFT JOIN return_history rh ON bb.borrow_id = rh.borrow_id
    WHERE rh.borrow_id IS NULL"; // Fetch only records where the borrow ID does not exist in return_history, indicating the book has not been returned yet

// Execute the query to get all currently borrowed books
$result_borrowed = mysqli_query($conn, $query_borrowed);

// Check if the query was successful
if ($result_borrowed) {
    echo '<div>';
    echo "<h3 class='text-center fw-bold' style='margin-bottom: 20px; font-size: 30px;'>Books Yet to Be Returned</h3>";
    echo "<ol style='padding-left: 20px;'>"; // Use <ol> for numbered list
    // Fetch the result as an associative array
    while ($row = mysqli_fetch_assoc($result_borrowed)) {
        echo "<li class='fw-bold' style='font-size:18px;'>{$row['title']} (Borrowed by {$row['username']} on {$row['borrow_date']})</li>";
    }
    echo "</ol>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error fetching currently borrowed books: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
