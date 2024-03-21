<style>

  .books-info {
    text-align: center;
    margin-bottom: 20px;
  }

  .books-info h3 {
    margin-bottom: 10px;
    color: green; /* Green color for headings */
  }

  .books-info p {
    font-size: 30px;
    font-weight: bold;
    color: green; /* Green color for numbers */
  }
</style>

<?php
// Include database connection
include 'config.php';

// Query to get the total number of borrowed books
$query_borrowed = "SELECT COUNT(*) AS total_borrowed FROM borrowed_books";

// Execute the query to get the total number of borrowed books
$result_borrowed = mysqli_query($conn, $query_borrowed);

// Check if the query to get the total number of borrowed books was successful
if ($result_borrowed) {
    // Fetch the result as an associative array
    $row_borrowed = mysqli_fetch_assoc($result_borrowed);
    ?>
    <div class="books-container">
        <div class="books-info">
            
            <p><?php echo $row_borrowed['total_borrowed']; ?></p>
            <h3 class="fw-bold">Total Borrowed Books</h3>
        </div>
        <?php
        // Query to get the total number of returned books
        $query_returned = "SELECT COUNT(*) AS total_returned FROM return_history";

        // Execute the query to get the total number of returned books
        $result_returned = mysqli_query($conn, $query_returned);

        // Check if the query to get the total number of returned books was successful
        if ($result_returned) {
            // Fetch the result as an associative array
            $row_returned = mysqli_fetch_assoc($result_returned);
            ?>
            <div class="books-info">
               
                <p><?php echo $row_returned['total_returned']; ?></p>
                <h3 class="fw-bold">Total Returned Books</h3>
            </div>
            <?php
        } else {
            // Display an error message if the query fails
            echo "Error fetching total returned books: " . mysqli_error($conn);
        }
        ?>
    </div>
    <?php
} else {
    // Display an error message if the query fails
    echo "Error fetching total borrowed books: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
