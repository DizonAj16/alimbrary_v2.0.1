<?php
// Include config file
require_once "config.php";

// Retrieve the search query
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Prepare the SQL statement with a placeholder for the search query
$sql = "SELECT return_history.return_id, borrowed_books.borrow_date, users.username, books.title, return_history.returned_date_time, borrowed_books.return_date,
                            DATEDIFF(return_history.returned_date_time, borrowed_books.borrow_date) AS days_borrowed 
                        FROM return_history
                        JOIN borrowed_books ON return_history.borrow_id = borrowed_books.borrow_id
                        JOIN users ON return_history.user_id = users.id
                        JOIN books ON return_history.book_id = books.book_id
                        WHERE users.username LIKE ?
                        ORDER BY return_history.returned_date_time DESC";

// Prepare and bind parameters
$stmt = mysqli_prepare($conn, $sql);
$searchQuery = '%' . $searchQuery . '%';
mysqli_stmt_bind_param($stmt, "s", $searchQuery);

// Execute the query
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<table class="table table-bordered table-hover mb-0" id="returnHistoryTable">
    <thead>
        <tr>
            <th>Return ID</th>
            <th>User</th>
            <th>Book Title</th>
            <th>Date Borrowed</th>
            <th>Date Returned</th>
            <th>Days Borrowed</th>
            <th>Return Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are any results
        if (mysqli_num_rows($result) > 0) {
            // Loop through each row in the result set
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['return_id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td class='fw-bold'>" . $row['title'] . "</td>";
                // Display borrowed date in 12-hour format with only the date
                echo "<td>" . date("F j, Y", strtotime($row['borrow_date'])) . "</td>";

                // Display returned date in 12-hour format with only the date
                echo "<td>" . date("F j, Y, h:i A", strtotime($row['returned_date_time'])) . "</td>";

                // Calculate days borrowed using DateTime objects
                $borrow_date = new DateTime($row['borrow_date']);
                $returned_date_time = new DateTime($row['returned_date_time']);
                $interval = $borrow_date->diff($returned_date_time);
                $days_borrowed = $interval->days;

                // Display days borrowed
                echo "<td>";
                if ($days_borrowed == 0) {
                    echo "Less than a day";
                } elseif ($days_borrowed >= 30) {
                    $months = floor($days_borrowed / 30);
                    $remaining_days = $days_borrowed % 30;
                    if ($remaining_days > 0) {
                        echo "$months month(s) $remaining_days day(s)";
                    } else {
                        echo "$months month(s)";
                    }
                } else {
                    echo "$days_borrowed day(s)";
                }
                echo "</td>";

                // Compare returned date with expected return date to determine return status
                $return_date = new DateTime($row['return_date']);
                if ($returned_date_time > $return_date) {
                    $return_status = "Late";
                } else {
                    $return_status = "On Time";
                }

                // Display return status
                echo "<td>";
                if ($return_status === "On Time") {
                    echo '<span class="badge bg-success">' . $return_status . '</span>';
                } else {
                    echo '<span class="badge bg-danger">' . $return_status . '</span>';
                }
                echo "</td>";

                echo "</tr>";
            }
        } else {
            // No matching records found
            echo '<tr><td colspan="7" class="text-center">No matching records found.</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php
// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>