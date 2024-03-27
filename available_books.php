<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fa-css/all.css">
    <style>
        .back-btn {
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <div class="container mt-3">
        <a href="dashboard.php" class="btn btn-primary back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2 class="mb-4 text-success fw-bold">Available Books</h2>
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by Title...">
        <table class="table table-striped table-hover table-bordered border-dark">
            <thead class="text-center">
                <tr>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="booksTable">
                <?php
                // Include config file
                require_once "config.php";

                // Attempt select query execution
                $sql = "SELECT book_id, title, availability FROM books WHERE availability = 'Available' ORDER BY book_id DESC";

                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='fw-bold'>" . $row['title'] . "</td>";
                            echo "<td class='text-center'>";
                            echo '<a href="view_available_books.php?book_id=' . $row['book_id'] . '" class="btn btn-dark rounded-2 btn-sm me-2" data-bs-toggle="tooltip" data-bs-title="View Book"><i class="fas fa-eye"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<tr><td colspan="3">No books found</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Oops! Something went wrong. Please try again later.</td></tr>';
                }

                // Close connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <script src="jquery/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var searchText = $(this).val().toLowerCase();
                $('#booksTable tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
                });
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
</body>

</html>