<?php
// Include config file
require_once "../config.php";

// Check if the book_id parameter is set
if(isset($_POST["book_id"]) && !empty($_POST["book_id"])){
    // Delete related records in return_history table
    $sql_delete_return = "DELETE FROM return_history WHERE borrow_id IN (SELECT borrow_id FROM borrowed_books WHERE book_id = ?)";
    if($stmt_delete_return = mysqli_prepare($conn, $sql_delete_return)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_delete_return, "i", $_POST["book_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt_delete_return)){
            // Deletion successful, now delete related records in borrowed_books table
            $sql_delete_borrowed = "DELETE FROM borrowed_books WHERE book_id = ?";
            if($stmt_delete_borrowed = mysqli_prepare($conn, $sql_delete_borrowed)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt_delete_borrowed, "i", $_POST["book_id"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt_delete_borrowed)){
                    // Deletion successful, now delete the book
                    $sql_delete_book = "DELETE FROM books WHERE book_id = ?";
                    if($stmt_delete_book = mysqli_prepare($conn, $sql_delete_book)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt_delete_book, "i", $_POST["book_id"]);
                        
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt_delete_book)){
                            // Book deletion successful
                            echo "Book deleted successfully.";
                            exit();
                        } else{
                            // Error during book deletion
                            echo "Oops! Something went wrong while deleting the book.";
                        }
                    }
                    // Close statement
                    mysqli_stmt_close($stmt_delete_book);
                } else{
                    // Error during deletion of related records in borrowed_books table
                    echo "Oops! Something went wrong while deleting related records in borrowed_books table.";
                }
            }
            // Close statement
            mysqli_stmt_close($stmt_delete_borrowed);
        } else{
            // Error during deletion of related records in return_history table
            echo "Oops! Something went wrong while deleting related records in return_history table.";
        }
    }
    // Close statement
    mysqli_stmt_close($stmt_delete_return);
}

// Close connection
mysqli_close($conn);
?>


