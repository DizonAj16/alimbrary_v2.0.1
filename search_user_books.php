<?php
// Include config file
require_once "config.php";

// Define a default SQL query
$sql = "SELECT * FROM books ORDER BY book_id DESC";

// Check if a search query is provided
if (isset($_POST['searchQuery']) && !empty($_POST['searchQuery'])) {
    // Sanitize the search query to prevent SQL injection
    $searchQuery = mysqli_real_escape_string($conn, $_POST['searchQuery']);
    // Modify the SQL query to include the search filter
    $sql = "SELECT * FROM books WHERE title LIKE '%$searchQuery%' ORDER BY book_id DESC";
}

// Attempt select query execution
if ($result = mysqli_query($conn, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo '<div class="card1">';
            echo '<img src="' . $row['image_path'] . '" alt="Book Image">';
            echo '<div class="info">';
            echo '<div class="mt-auto">';
            // Display the title of the book
            echo '<div class="heading1 mb-2">' . $row['title'] . '</div>';
            // Display availability badge
            echo '  <span class="badge bg-' . (($row['availability'] == 'Available') ? 'success' : 'danger') . ' text-light mb-2 badge-lg">' . $row['availability'] . '</span>';

            // Display buttons
            echo '<div class="d-flex justify-content-center">';
            echo '<a href="userviewbook.php?book_id=' . $row['book_id'] . '" class="btn btn-primary me-2 fw-bold">Read More</a>';
            echo '<a href="borrow.php?book_id=' . $row['book_id'] . '" class="btn btn-warning fw-bold">Borrow Book</a>';
            echo '</div>'; // Close d-flex div
            echo '</div>'; // Close mt-auto div
            echo '</div>'; // Close info div
            echo '</div>'; // Close card1 div
        }
        // Free result set
        mysqli_free_result($result);
    } else {
        echo '<div class="container-fluid">';
        echo '    <div class="alert alert-danger" role="alert"><em>No results found</em></div>';
        echo '</div>';
    }
} else {
    echo '<div class="container">';
    echo '<div class="alert alert-danger" role="alert">Unable to fetch books</div>';
    echo '</div>';
}


// Close connection
mysqli_close($conn);