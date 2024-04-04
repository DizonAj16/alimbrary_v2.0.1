<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Available Books</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../fa-css/all.css">
    <style>
        .back-btn {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container mt-3">
        <a href="../admin/dashboard.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2 class="mb-4 text-danger fw-bold">Not Available Books</h2>
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by Title...">

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered border-dark">
                <thead class="text-center">
                    <tr>
                        <th>Title</th>
                        <th>Borrowed By</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="booksTable">
                <?php
// Include config file
require_once "../config.php";

// Attempt select query execution for borrowed books
$query_borrowed = "
    SELECT b.book_id, b.title, u.username, bb.borrow_date, bb.return_date
    FROM borrowed_books bb
    JOIN books b ON bb.book_id = b.book_id
    JOIN users u ON bb.user_id = u.id
    LEFT JOIN return_history rh ON bb.borrow_id = rh.borrow_id
    WHERE rh.borrow_id IS NULL
    ORDER BY bb.borrow_id DESC";

$current_datetime = date_create("now", new DateTimeZone('Asia/Manila'))->format("Y-m-d H:i:s"); // Current date and time in Manila time zone

if ($result_borrowed = mysqli_query($conn, $query_borrowed)) {
    if (mysqli_num_rows($result_borrowed) > 0) {
        while ($row_borrowed = mysqli_fetch_assoc($result_borrowed)) {
            echo "<tr>";
            echo "<td class='fw-bold'>" . $row_borrowed['title'] . "</td>";
            echo "<td class='text-center text-primary fw-bold'>" . $row_borrowed['username'] . "</td>";
            echo "<td>" . date("F j, Y, h:i A", strtotime($row_borrowed['borrow_date'])) . "</td>";
            echo "<td>" . date("F j, Y, h:i A", strtotime($row_borrowed['return_date'])) . "</td>";

            // Calculate status based on return date and time
            $return_datetime = date_create($row_borrowed['return_date'], new DateTimeZone('Asia/Manila'))->format("Y-m-d H:i:s");

            $status = "";

            if ($return_datetime && $current_datetime > $return_datetime) {
                $status = "Overdue";
            }

            echo "<td class='text-center text-danger fw-bold'>$status</td>";

            echo "<td class='text-center'><a href='view_not_available_books.php?book_id=" . $row_borrowed['book_id'] . "' class='btn btn-dark btn-sm' data-bs-toggle='tooltip' data-bs-title='View'><i class='fas fa-eye'></i></a></td>"; // Action for viewing book details
            echo "</tr>";
        }
        mysqli_free_result($result_borrowed);
    } else {
        echo "<tr><td colspan='6'>No books currently borrowed</td></tr>";
    }
} else {
    echo "<tr><td colspan='6'>Oops! Something went wrong fetching borrowed books. Please try again later.</td></tr>";
}

// Close connection
mysqli_close($conn);
?>

                </tbody>
                <div id="noResults" class="alert alert-danger mt-3 mb-3" style="display: none;">
                    No results found
                </div>
            </table>
        </div>
    </div>
</body>
<script src="../jquery/jquery-3.5.1.min.js"></script>
<script src="../js/bootstrap.bundle.js"></script>
<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#booksTable tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
            if ($('tbody tr:visible').length === 0) {
                $('#noResults').show();
                $('thead').hide();
            } else {
                $('#noResults').hide();
                $('thead').show();
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>

</html>
