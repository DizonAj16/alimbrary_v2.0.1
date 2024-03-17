<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navstyle.css">
    <link rel="stylesheet" href="fa-css/all.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #con {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-top: 85px;
        }

        .float-start {
            color: black;
        }
        
        th,
        td {
                border: 1px solid #dee2e6;
                padding: 10px;
                text-align: start;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary,
        .btn-danger {
            margin: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title rounded-3 p-1">
                <span class="letter-a">A</span>
                <span class="letter-l">l</span>
                <span class="letter-i">i</span>
                <span class="letter-m">m</span>
                <span class="letter-b">b</span>
                <span class="letter-r">r</span>
                <span class="letter-a">a</span>
                <span class="letter-r">r</span>
                <span class="letter-y">y</span>
                <img src="Images/icons8-book-50.png" alt="" style="margin-left: 5px;">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userbook.php"><i class="fa fa-book fa-lg"></i> Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="borrowedbooks.php"><i class="fas fa-book-reader fa-lg"></i> Borrowed Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userreturnhistory.php"><i class="fa fa-book fa-lg"></i> Returned Books</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user fa-lg"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                            <li><a class="dropdown-item" href="reset-password.php"><i class="fas fa-undo"></i> Reset Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container" id="con">  
        <?php
        // Include config file
        require_once "config.php";

        // Query to retrieve borrowed books information excluding returned books
        $borrowed_books_sql = "SELECT borrowed_books.borrow_id, books.title, users.username, borrowed_books.borrow_date, borrowed_books.return_date 
                                FROM borrowed_books
                                JOIN books ON borrowed_books.book_id = books.book_id
                                JOIN users ON borrowed_books.user_id = users.id
                                WHERE borrowed_books.user_id = ? AND borrowed_books.borrow_id NOT IN (SELECT return_history.borrow_id FROM return_history WHERE status = 'returned')
                                ORDER BY borrowed_books.borrow_id DESC";

        if ($stmt = mysqli_prepare($conn, $borrowed_books_sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

            // Execute the prepared statement
            mysqli_stmt_execute($stmt);

            // Store the result
            mysqli_stmt_store_result($stmt);

            // Check if there are borrowed books
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $borrow_id, $book_title, $username, $borrow_date, $return_date);

                // Start displaying borrowed books
        ?>
                <h2 class="float-start">Borrowed Books</h2>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Borrow ID</th>
                                        <th>Book Title</th>
                                        <th>Borrow Date</th>
                                        <th>Borrow Until</th>
                                        <th>Days Left</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch records
                                    while (mysqli_stmt_fetch($stmt)) {
                                        echo "<tr>";
                                        echo "<td>" . $borrow_id . "</td>";
                                        echo "<td class='fw-bold'>" . $book_title . "</td>";
                                        echo "<td>" . $borrow_date . "</td>";
                                        echo "<td>" . $return_date . "</td>";
                                        
                                        $today = date('Y-m-d');
                                        $diff = strtotime($return_date) - strtotime($today);
                                        $days_left = floor($diff / (60 * 60 * 24));

                                        echo "<td>" . $days_left . "</td>"; 
                                        echo "<td>";
                                       
                                        echo "<a href='return.php?borrow_id=" . $borrow_id . "' class='btn btn-danger btn-sm text-light fw-bold'>Return Book</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }


                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        <?php
            } else {
                // Display message if there are no borrowed books or all books are returned
                echo "<div class='container mt-5 bg-danger text-light p-4 rounded-3'>";
                echo "<h2>No Borrowed Books</h2>";
                echo "<p class='lead'>You haven't borrowed any books yet or all borrowed books are returned.</p>";
                echo "</div>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>
</body>

</html>