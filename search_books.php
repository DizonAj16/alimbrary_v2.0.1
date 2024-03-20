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
                url: "deletebook.php",
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
require_once "config.php";

// Check if searchText is set and not empty
if (isset($_POST["searchText"])) {
    // Sanitize search text
    $searchText = trim($_POST["searchText"]);

    // Prepare the SQL statement
    $sql = "SELECT * FROM books WHERE title LIKE ? ORDER BY book_id DESC";

    // Bind parameters and execute the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $param_search = "%" . $searchText . "%";
        mysqli_stmt_bind_param($stmt, "s", $param_search);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if there are results
        if (mysqli_num_rows($result) > 0) {
            // Loop through results and generate HTML for book cards
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
                echo '<div class="card h-100 border border-primary rounded-5">';
                echo '<div class="d-flex justify-content-center align-items-center mt-2" style="height: 200px;">';

                // Display the image if image path exists
                if (!empty($row['image_path'])) {
                    echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Book Image" style="max-height: 200px; max-width: 150px;">';
                } else {
                    echo '<span class="text-center">No image available</span>';
                }
                echo '</div>';

                echo '<div class="card-body">';
                echo '<h5 class="card-title text-center mb-3 fw-bold" style="height: 50px; overflow: hidden; text-overflow: ellipsis; font-size: 18px;" title="' . $row['title'] . '">' . $row['title'] . '</h5>';
                echo '<p class="card-text text-center mb-3" style="font-size: 16px;">';
                echo '  <span class="badge bg-' . (($row['availability'] == 'Available') ? 'success' : 'danger') . ' text-light">' . $row['availability'] . '</span>';
                echo '</p>';
                echo '<div class="d-flex justify-content-center">';
                echo '<a href="adminviewbook.php?book_id=' . $row['book_id'] . '" class="btn btn-dark rounded-2 btn-sm me-2" title="View Book" data-bs-toggle="tooltip" data-bs-title="View"><i class="fas fa-book-open"></i> View</a>';
                echo '<a href="updatebook.php?book_id=' . $row['book_id'] . '" class="btn btn-info text-light rounded-2 btn-sm me-2" title="Update Book" data-bs-toggle="tooltip"><span class="fa fa-pencil fa-lg"></span> Update</a>';
                echo '<a href="#" class="btn btn-danger rounded-2 btn-sm delete-btn" data-book-id="' . $row['book_id'] . '" title="Delete Book" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash-alt"></i> Delete</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col">';
            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col">';
        echo '<div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div>';
        echo '</div>';
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // If searchText is not set or empty, return an error message
    echo '<div class="col">';
    echo '<div class="alert alert-danger"><em>Error: Search text not provided.</em></div>';
    echo '</div>';
}
?>
