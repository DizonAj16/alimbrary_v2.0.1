<table class="table table-bordered table-responsive table-hover mb-0" id="borrowHistoryTable">
    <thead class="text-center">
        <tr>
            <th>Borrow ID</th>
            <th>Username</th>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Return Until</th>
            <th>Days Left</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Include config file
        require_once "config.php";

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
                    <td class="text-center">' . ($row['return_date'] ? date("F j, Y", strtotime($row['return_date'])) : 'Not returned') . '</td>';

                // Calculate the days left
                echo '<td>';
                if ($row['return_id']) {
                    // If returned, display a success badge and leave the days left column blank
                } else {
                    // If not returned, calculate days left
                    // Calculate the days left until return
                    $current_date = date_create(date("Y-m-d")); // Current date
                    $return_date_obj = date_create($row['return_date']); // Return date
                    $days_left = date_diff($current_date, $return_date_obj)->format("%r%a"); // Days left

                    if ($days_left == 0) {
                        $days_left = 'Less than a day';
                    } elseif ($days_left < 0) {
                        $days_left = '<p class="text-danger fw-bold">Overdue</p>';
                    } elseif ($days_left >= 30) {
                        $months = floor($days_left / 30);
                        $remaining_days = $days_left % 30;
                        $days_left = $months . ' month(s) ' . $remaining_days . ' day(s)';
                    } else {
                        $days_left = $days_left . ' day(s)';
                    }
                    echo $days_left;
                }
                echo '</td>';

                // Display status
                echo '<td class="text-center">';
                if ($row['return_id']) {
                    // If returned, display a success badge
                    echo '<span class="badge bg-success">Returned</span>';
                } else {
                    // If not returned, display a danger badge
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
