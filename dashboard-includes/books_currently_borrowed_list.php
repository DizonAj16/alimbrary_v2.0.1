<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Availability</title>
    <link rel="stylesheet" href="../fa-css/all.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-3">
        <?php
        // Include config file
        require_once "../config.php";

        // Attempt select query execution
        $sql = "SELECT * FROM books";
        if ($result = mysqli_query($conn, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="card-deck">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="card mb-3">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                    echo '<p class="card-text">';

                    if ($row['availability'] == 'Available') {
                        echo '<span class="badge badge-success">Available</span>';
                    } else {
                        echo '<span class="badge badge-danger">Not Available</span>';
                    }

                    echo '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                // Free result set
                mysqli_free_result($result);
            } else {
                echo '<div class="alert alert-danger">No books found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Oops! Something went wrong. Please try again later.</div>';
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>

    <script src="../jquery/jquery-3.5.1.slim.min.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    
</body>
</html>
