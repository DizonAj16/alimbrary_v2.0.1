<?php
// Include config file
require_once "../config.php";

// Retrieve the search query
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Prepare the SQL statement with placeholders for the search query
$sql = "SELECT return_history.return_id, borrowed_books.borrow_date, users.username, books.title, return_history.returned_date_time, borrowed_books.return_date,
                            DATEDIFF(return_history.returned_date_time, borrowed_books.borrow_date) AS days_borrowed 
                        FROM return_history
                        JOIN borrowed_books ON return_history.borrow_id = borrowed_books.borrow_id
                        JOIN users ON return_history.user_id = users.id
                        JOIN books ON return_history.book_id = books.book_id
                        WHERE users.username LIKE ? OR books.title LIKE ?
                        ORDER BY return_history.returned_date_time DESC";

// Prepare and bind parameters
$stmt = mysqli_prepare($conn, $sql);
$searchQuery = '%' . $searchQuery . '%';
mysqli_stmt_bind_param($stmt, "ss", $searchQuery, $searchQuery);

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<table class="table table-bordered table-hover mb-0" id="returnHistoryTable">
    <thead class="text-center">
        <tr>
            <th>R.Id</th>
            <th>User</th>
            <th>Book Title</th>
            <th>Date Borrowed</th>
            <th>Date Returned</th>
            <th>Time Borrowed</th>
            <th>R.Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are any results
        if (mysqli_num_rows($result) > 0) {
            // Loop through each row in the result set
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td class='text-center'>" . $row['return_id'] . "</td>";
                echo "<td class='text-center'>" . $row['username'] . "</td>";
                echo "<td class='fw-bold'>" . $row['title'] . "</td>";
                // Display borrowed date in the specified format
                echo "<td class='text-center'>" . date("F j, Y, h:i A", strtotime($row['borrow_date'])) . "</td>";

                // Display returned date in the specified format or "Not returned" if null
                echo "<td class='text-center'>" . ($row['returned_date_time'] ? date("F j, Y, h:i A", strtotime($row['returned_date_time'])) : 'Not returned') . "</td>";

                // Calculate days borrowed and display in the specified format
                $borrow_date = new DateTime($row['borrow_date']);
                $returned_date_time = new DateTime($row['returned_date_time']);
                $interval = $borrow_date->diff($returned_date_time);
                $days_borrowed = $interval->days;
                $hours_borrowed = $interval->h;
                $minutes_borrowed = $interval->i;

                // Display time borrowed
                echo "<td class='text-center'>";
                if ($days_borrowed == 0 && $hours_borrowed == 0) {
                    echo "$minutes_borrowed minute(s)";
                } elseif ($days_borrowed == 0) {
                    echo "$hours_borrowed hour(s), $minutes_borrowed minute(s)";
                } else {
                    echo "$days_borrowed day(s), $hours_borrowed hour(s), $minutes_borrowed minute(s)";
                }
                echo "</td>";


                // Determine return status and display as badge
                echo "<td class='text-center'>";
                $return_status = ($row['returned_date_time'] > $row['return_date']) ? "Late" : "On Time";
                $badge_class = ($return_status === "On Time") ? "bg-success" : "bg-danger";
                echo '<span class="badge ' . $badge_class . '">' . $return_status . '</span>';
                echo "</td>";

                echo "</tr>";
            }
        } else {
            // No matching records found
            echo '<tr><td colspan="7" class="text-center text-danger" >No matching records found.</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php
// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>