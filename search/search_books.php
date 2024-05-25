<script>
    $(document).ready(function() {

        var bookIdToDelete;

        $(".delete-btn").click(function() {
            bookIdToDelete = $(this).data("book-id");
        });

        // Handle confirm deletion button click
        $("#confirmDeleteBtn").click(function() {
            // AJAX request to delete the book
            $.ajax({
                url: "../admin/deletebook.php",
                type: "POST",
                data: {
                    book_id: bookIdToDelete
                },
                success: function(data) {
                    // Reload the page after successful deletion
                    location.reload();
                    alert('Book Deleted Successfully...')
                },
                error: function() {
                    alert("Error deleting book.");
                }
            });
        });
    });
</script>








<?php
// Include config file
require_once "../config.php";

// Define a default SQL query
$sql = "SELECT * FROM books";

// Initialize an empty array to hold parameters for prepared statement
$params = array();

// Check if searchText is set and not empty
if (isset($_POST["searchText"]) && !empty($_POST["searchText"])) {
    // Sanitize search query to prevent SQL injection
    $searchQuery = "%" . mysqli_real_escape_string($conn, $_POST["searchText"]) . "%";

    // Append search conditions to the SQL query
    $sql .= " WHERE (title LIKE ? OR genre LIKE ?)";
    
    // Add search parameters to the array
    $params[] = $searchQuery;
    $params[] = $searchQuery;
}

// Check if a genre is selected
if (isset($_POST['genre']) && !empty($_POST['genre'])) {
    // Sanitize the genre to prevent SQL injection
    $selectedGenre = mysqli_real_escape_string($conn, $_POST['genre']);

    // Split the selected genre into individual words
    $selectedGenreWords = explode(" ", $selectedGenre);

    // Initialize an array to store conditions for each genre word
    $genreConditions = [];

    // Build conditions for each genre word
    foreach ($selectedGenreWords as $word) {
        $genreConditions[] = "genre LIKE ?";
        // Add genre parameters to the array
        $params[] = "%" . $word . "%";
    }

    // Add genre conditions to the SQL query
    $sql .= (isset($_POST["searchText"]) && !empty($_POST["searchText"])) ? " AND (" . implode(" OR ", $genreConditions) . ")" : " WHERE (" . implode(" OR ", $genreConditions) . ")";
}

$sql .= " ORDER BY book_id DESC";

// Prepare and bind parameters for the statement
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
    }
    // Execute the statement
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if there are results
    if ($result && mysqli_num_rows($result) > 0) {
        // Loop through results and generate HTML for book cards
        while ($row = mysqli_fetch_assoc($result)) {
            // Output HTML for book cards
            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
            echo '<div class="card h-100 rounded">';
            echo '<div class="d-flex justify-content-center align-items-center mt-2" style="height: 200px;">';
            // Display the image if image path exists
            if (!empty($row['image_path'])) {
                echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Book Image" style="max-height: 200px; max-width: 150px;">';
            } else {
                echo '<span class="text-center">No image available</span>';
            }
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title text-center mb-3 fw-bold text-light" style="height: 50px; font-size: 18px;" title="' . $row['title'] . '">' . $row['title'] . '</h5>';
            echo '<p class="card-text text-center mb-3" style="font-size: 16px;">';
            echo '  <span class="badge bg-' . (($row['availability'] == 'Available') ? 'success' : 'danger') . ' text-light">' . $row['availability'] . '</span>';
            echo '</p>';
            echo '<div class="d-flex justify-content-center">';
            echo '<div class="rounded-5 d-flex">';
            echo '<a href="adminviewbook.php?book_id=' . $row['book_id'] . '" class="btn text-light rounded-2 btn-lg" title="View Book"><i class="fas fa-eye"></i></a>';
            echo '<a href="updatebook.php?book_id=' . $row['book_id'] . '" class="btn text-light rounded-2 btn-lg" title="Update Book"><span class="fa fa-pencil fa-lg"></span></a>';
            echo '<a href="#" class="btn rounded-2 btn-lg text-light delete-btn" data-book-id="' . $row['book_id'] . '" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete Book"><i class="fas fa-trash-alt"></i></a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col">';
        echo '<div class="alert alert-danger text-danger"><em>No records were found.</em></div>';
        echo '</div>';
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    // Error handling
    echo '<div class="col">';
    echo '<div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div>';
    echo '</div>';
}

// Close connection
mysqli_close($conn);
?>

