<style>
  .available-books-container {
    text-align: center;
    color: white;
  }

  .available-books-count {
    font-size: 30px;
    color: white; 
    font-weight: bold;
  }
</style>

<?php
// Include database connection
include 'config.php';

// Query to get the total count of available books
$query = "
    SELECT COUNT(*) AS total_available
    FROM books
    WHERE availability = 'available'";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Display the total count of available books
    echo '<div class="available-books-container">';
    echo "<h3><span class='available-books-count'>{$row['total_available']} Available books</span></h3>";
    echo "</div>";
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
