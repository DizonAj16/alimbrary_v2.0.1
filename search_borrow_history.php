<?php
// Include config file
require_once "config.php";

// Retrieve the search query
$searchQuery = $_GET['q'];

// Prepare the SQL statement with a placeholder for the search query
$sql = "SELECT borrowed_books.borrow_id, borrowed_books.borrow_date, borrowed_books.return_date, users.id, users.username, books.title 
        FROM borrowed_books 
        INNER JOIN users ON borrowed_books.user_id = users.id
        INNER JOIN books ON borrowed_books.book_id = books.book_id
        WHERE users.username LIKE ?
        ORDER BY borrowed_books.borrow_id DESC";

// Prepare and bind parameters
$stmt = mysqli_prepare($conn, $sql);
$searchQuery = '%' . $searchQuery . '%';
mysqli_stmt_bind_param($stmt, "s", $searchQuery);

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Display the filtered results in the table format
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $row['borrow_id'] . '</td>
                <td>' . $row['username'] . '</td>
                <td class="fw-bold">' . $row['title'] . '</td>
                <td>' . $row['borrow_date'] . '</td>
                <td>' . ($row['return_date'] ? $row['return_date'] : 'Not returned') . '</td>';

        // Calculate days left or days since return
        // Display the result in the table row
        echo '</tr>';
    }
} else {
    // No matching records found
    echo '<tr><td colspan="6" class="text-center">No matching records found.</td></tr>';
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
