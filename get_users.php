<style>
  .user-count-container {
    text-align: center;
    color: green;
  }

  .user-count {
    font-size: 30px;
    color: green; /* Dark color for the number */
    font-weight: bold;
  }
</style>

<?php
// Include database connection
include 'config.php';

// Query to count the number of users
$query = "SELECT COUNT(*) AS user_count FROM users";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    
    // Display the number of users
    echo '<div class="user-count-container">';
    echo "<h3><span class='user-count'>" . $row['user_count'] . " Users</span></h3>";
    echo '</div>';
} else {
    // Display an error message if the query fails
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
