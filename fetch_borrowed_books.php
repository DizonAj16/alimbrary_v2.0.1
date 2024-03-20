<?php
// Include config file
require_once "config.php";

// Check if search term is provided via GET request
if (isset($_GET['search_term'])) {
    $searchTerm = '%' . $_GET['search_term'] . '%';

    // Query to search for borrowed books by title
    $search_sql = "SELECT borrowed_books.borrow_id, books.title, borrowed_books.borrow_date, borrowed_books.return_date,
                    DATEDIFF(borrowed_books.return_date, CURDATE()) AS days_left
                    FROM borrowed_books
                    JOIN books ON borrowed_books.book_id = books.book_id
                    WHERE borrowed_books.user_id = ? AND borrowed_books.borrow_id NOT IN (SELECT return_history.borrow_id FROM return_history WHERE status = 'returned')
                    AND books.title LIKE ?
                    ORDER BY borrowed_books.borrow_id DESC";
    
    if ($stmt = mysqli_prepare($conn, $search_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "is", $_SESSION["id"], $searchTerm);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Store the result
        $result = mysqli_stmt_get_result($stmt);
        
        // Fetch and store results in an array
        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
       
        // Return results as JSON
        echo json_encode($books);
    }
    // Close connection
    mysqli_close($conn);
}
?>
