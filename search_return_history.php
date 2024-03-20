<?php
// Include config file
require_once "config.php";

// Check if the search query parameter is set
if (isset($_GET['q'])) {
    // Sanitize the search query
    $searchQuery = mysqli_real_escape_string($conn, $_GET['q']);

    // Define SQL query to fetch return history data based on the search query
    $return_history_sql = "SELECT return_history.return_id, borrowed_books.borrow_date, users.username, books.title, return_history.returned_date_time 
                            FROM return_history
                            JOIN users ON return_history.user_id = users.id
                            JOIN books ON return_history.book_id = books.book_id
                            JOIN borrowed_books ON return_history.borrow_id = borrowed_books.borrow_id
                            WHERE users.username LIKE '%$searchQuery%'
                            ORDER BY return_history.returned_date_time DESC";

    // Execute the query
    $result = mysqli_query($conn, $return_history_sql);

    // Check if there are any matching results
    if (mysqli_num_rows($result) > 0) {
        // Start building the HTML content for the table
        $output = '<thead>
                        <tr>
                            <th>Return ID</th>
                            <th>User</th>
                            <th>Book Title</th>
                            <th>Date Borrowed</th>
                            <th>Date Returned</th>
                            <th>Days Borrowed</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Loop through each row in the result set
        while ($row = mysqli_fetch_assoc($result)) {
            // Build table rows with data from the result set
            $output .= "<tr>
                            <td>{$row['return_id']}</td>
                            <td>{$row['username']}</td>
                            <td class='fw-bold'>{$row['title']}</td>
                            <td>{$row['borrow_date']}</td>
                            <td>{$row['returned_date_time']}</td>";
            
            // Calculate the days borrowed
            $date1 = strtotime($row['borrow_date']);
            $date2 = strtotime($row['returned_date_time']);
            $diff = abs($date2 - $date1);
            $days_borrowed = floor($diff / (60 * 60 * 24));

            // Display "Less than a day" if days borrowed is 0
            if ($days_borrowed == 0) {
                $output .= "<td>Less than a day</td>";
            } else {
                $output .= "<td>$days_borrowed day(s)</td>";
            }
            
            $output .= "</tr>";
        }

        // Close the HTML content for the table
        $output .= '</tbody>';

        // Output the HTML content
        echo $output;
    } else {
        // No matching results found
        echo '<tr><td colspan="6" class="text-center">No matching records found.</td></tr>';
    }

    // Free result set
    mysqli_free_result($result);
} else {
    // If the search query parameter is not set, return an error message
    echo "Error: No search query provided.";
}

// Close connection
mysqli_close($conn);
?>
