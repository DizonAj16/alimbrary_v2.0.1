<?php
// Include database connection
include 'config.php';
?>

<div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <?php
    // Query to get the total number of borrowed books
    $query_borrowed = "SELECT COUNT(*) AS total_borrowed FROM borrowed_books";

    // Execute the query to get the total number of borrowed books
    $result_borrowed = mysqli_query($conn, $query_borrowed);

    // Check if the query to get the total number of borrowed books was successful
    if ($result_borrowed) {
        // Fetch the result as an associative array
        $row_borrowed = mysqli_fetch_assoc($result_borrowed);
        ?>
        <div style="text-align: center;">
            <h3 style="margin-bottom: 10px;">Total Borrowed Books</h3>
            <p style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $row_borrowed['total_borrowed']; ?></p>
        </div>
        <?php
    } else {
        // Display an error message if the query fails
        echo "Error fetching total borrowed books: " . mysqli_error($conn);
    }

    // Query to get the total number of returned books
    $query_returned = "SELECT COUNT(*) AS total_returned FROM return_history";

    // Execute the query to get the total number of returned books
    $result_returned = mysqli_query($conn, $query_returned);

    // Check if the query to get the total number of returned books was successful
    if ($result_returned) {
        // Fetch the result as an associative array
        $row_returned = mysqli_fetch_assoc($result_returned);
        ?>
        <div style="text-align: center; margin-top: 20px;">
            <h3 style="margin-bottom: 10px;">Total Returned Books</h3>
            <p style="font-size: 24px; font-weight: bold; color: #333;"><?php echo $row_returned['total_returned']; ?></p>
        </div>
        <?php
    } else {
        // Display an error message if the query fails
        echo "Error fetching total returned books: " . mysqli_error($conn);
    }
    ?>
</div>

<?php
// Close database connection
mysqli_close($conn);
?>
