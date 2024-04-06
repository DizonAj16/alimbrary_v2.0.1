<table class="table table-bordered table-responsive table-hover mb-0" id="borrowHistoryTable">
    <thead class="text-center">
        <tr>
            <th>Borrow ID</th>
            <th>Username</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Return Until</th>
            <th>Time Left</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Include config file
        require_once "../config.php";

        // Retrieve the search query
        $searchQuery = $_GET['q'];

        // Prepare the SQL statement with a placeholder for the search query
        $sql = "SELECT borrowed_books.borrow_id, borrowed_books.borrow_date, borrowed_books.return_date, borrowed_books.return_date, users.id, users.username, books.title, return_history.return_id 
                FROM borrowed_books 
                INNER JOIN users ON borrowed_books.user_id = users.id
                INNER JOIN books ON borrowed_books.book_id = books.book_id
                LEFT JOIN return_history ON borrowed_books.borrow_id = return_history.borrow_id
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
                    <td class="text-center">' . $row['borrow_id'] . '</td>
                    <td class="text-center">' . $row['username'] . '</td>
                    <td class="fw-bold text-start">' . $row['title'] . '</td>
                    <td class="text-center">' . date("F j, Y, h:i A", strtotime($row['borrow_date'])) . '</td>
                    <td class="text-center">' . ($row['return_date'] ? date("F j, Y, h:i A", strtotime($row['return_date'])) : 'Not returned') . '</td>';

                // Calculate the time left
                echo '<td>';
                if ($row['return_id']) {
                    // If returned, display a success badge and leave the time left column blank

                } else {
                    // If not returned, calculate time left
                    $current_date = time() + (6 * 60 * 60); 
                    $return_date = strtotime($row['return_date']); 
                    $time_left = $return_date - $current_date; 

                    $days = floor($time_left / (60 * 60 * 24));
                    $hours = floor(($time_left % (60 * 60 * 24)) / (60 * 60));
                    $minutes = floor(($time_left % (60 * 60)) / 60);

                    if ($days < 0) {
                        echo '<p class="text-danger">Overdue</p>';
                    } elseif ($days == 0 && $hours < 24) {
                        echo '<p class="text-danger">Book nearing due: ' . $hours . ' hour(s), ' . $minutes . ' minute(s)</p>';
                    } else {
                        echo $days . ' day(s), ' . $hours . ' hour(s), ' . $minutes . ' minute(s)';
                    }
                }
                echo '</td>';

                // Display status
                echo '<td class="text-center">';
                if ($row['return_id']) {
                    echo '<span class="badge bg-success">Returned</span>';
                } else {       
                    echo '<span class="badge bg-danger">Not returned</span>';
                }
                echo '</td>';

                echo '</tr>';
            }
        } else {
            // No matching records found
            echo '<tr><td colspan="7" class="text-center">No matching records found.</td></tr>';
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>
    </tbody>
</table>
