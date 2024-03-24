<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <!-- Bootstrap CSS link -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS link -->
    <link rel="stylesheet" href="fa-css/all.css">
    <!-- Custom CSS -->
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
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody id="booksTable">
                <?php
                // Include config file
                require_once "config.php";

                // Attempt select query execution
                $sql = "SELECT title, availability FROM books WHERE availability = 'Available' ORDER BY book_id DESC";

                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['title'] . "</td>";
                            echo "<td>";
                            if ($row['availability'] == 'Available') {
                                echo '<span class="badge bg-success">Available</span>';
                            } else {
                                echo '<span class="badge bg-danger">Not Available</span>';
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<tr><td colspan="2">No books found</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="2">Oops! Something went wrong. Please try again later.</td></tr>';
                }

                // Close connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS and custom JavaScript can be included at the end of the body -->
    <script src="jquery/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script>
        $(document).ready(function(){
            $('#searchInput').on('keyup', function(){
                var searchText = $(this).val().toLowerCase();
                $('#booksTable tr').filter(function(){
                    $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
                });
            });
        });
    </script>
</body>
</html>
