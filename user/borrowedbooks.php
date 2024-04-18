<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: ../login.php");
    exit;
}
?>

<?php
// Include config file
require_once "../config.php";

// Fetch user's profile image path from the database
$user_id = $_SESSION["id"];
$sql = "SELECT image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $profile_image);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../external-css/navigation.css">
    <link rel="stylesheet" href="../fa-css/all.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <script defer src="../js/bootstrap.bundle.js"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('../images/glassmorphism.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 85px;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 0;
        }

        table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }


        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            cursor: pointer;
            border-radius: 50%;
        }

        #backToTopBtn:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        footer {
            background-color: black;
            margin-top: auto;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title p-1">
                <img class="logo" src="../Images/logo.png" alt="">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="userwelcome.php"><i class="fa-solid fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userdashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userbook.php"><i class="fa fa-book fa-lg"></i> Browse Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="borrowedbooks.php"><i class="fas fa-book-reader fa-lg"></i> Borrowed Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userreturnhistory.php"><i class="fa fa-history fa-lg"></i> Returned Books</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            // Display user's profile image or default user icon
                            if (!empty($profile_image)) {
                                echo '<img src="../' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="rounded-circle" style="width: 32px; height: 32px;">';
                            } else {
                                echo '<i class="fa fa-user fa-lg"></i>';
                            }
                            ?>
                            <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                            <li><a class="dropdown-item" href="../reset-password.php"><i class="fas fa-unlock"></i> Reset Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        // Set Manila time zone
        date_default_timezone_set('Asia/Manila');

        // Include config file
        require_once "../config.php";

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
                <div class="card mt-2">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">Borrowed Books</h2>
                        <div class="d-flex">
                            <input type="text" id="searchInput" class="form-control me-2" placeholder="Search by Book Title..." style="width: 200px;">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="text-center">
                                    <tr>
                                        <th>B.Id</th>
                                        <th>Book Title</th>
                                        <th>Borrow Date</th>
                                        <th>Borrow Until</th>
                                        <th>Time Left</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch records
                                    while (mysqli_stmt_fetch($stmt)) {
                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $borrow_id . "</td>";
                                        echo "<td class='fw-bold'>" . $book_title . "</td>";
                                        // Display borrowed date in 12-hour format with only the date
                                        echo "<td>" . date("F j, Y, h:i A", strtotime($borrow_date)) . "</td>";


                                        // Display return date in 12-hour format with only the date
                                        echo "<td>" . date("F j, Y, h:i A", strtotime($return_date)) . "</td>";

                                        // Calculate time left
                                        $now = time();
                                        $return_time = strtotime($return_date);
                                        $diff = $return_time - $now;
                                        $days = floor($diff / (60 * 60 * 24));
                                        $hours = floor(($diff - $days * 60 * 60 * 24) / (60 * 60));
                                        $minutes = floor(($diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                                        $seconds = $diff % 60;

                                        // Display time left
                                        $time_left = "";
                                        if ($days > 0) {
                                            $time_left .= "$days day(s) ";
                                        }
                                        if ($hours > 0) {
                                            $time_left .= "$hours hour(s) ";
                                        }
                                        if ($minutes > 0) {
                                            $time_left .= "$minutes minute(s) ";
                                        }

                                        echo "<td>";
                                        // Check if the book is nearing due or overdue
                                        if ($diff <= 0) {
                                            echo "<p class='text-danger'>Overdue</p>";
                                        } elseif ($diff <= 86400) {
                                            echo "<p class='text-danger'>$time_left - Book is nearing due</p>";
                                        } else {
                                            echo $time_left;
                                        }
                                        echo "</td>";


                                        echo "<td>";
                                        // Check if the book is overdue
                                        if ($now > $return_time) {
                                            echo "<a href='return.php?borrow_id=" . $borrow_id . "' class='btn btn-danger btn-sm text-light fw-bold'>Return Book</a>";
                                        } else {
                                            echo "<a href='return.php?borrow_id=" . $borrow_id . "' class='btn btn-danger btn-sm text-light fw-bold'>Return Book</a>";
                                        }
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div id="noResults" class="alert alert-danger ms-2 me-2" style="display: none;">
                        No results found
                    </div>
                </div>
        <?php
            } else {
                // Display message if there are no borrowed books or all books are returned
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<h4 class='alert-heading'>No Borrowed Books</h4>";
                echo "<p class='mb-0'>You haven't borrowed any books yet or all borrowed books are returned.</p>";
                echo "</div>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>

    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').keyup(function() {
                var searchText = $(this).val().toLowerCase();
                var found = false;
                $('tbody tr').each(function() {
                    var title = $(this).find('td:eq(1)').text().toLowerCase();
                    if (title.indexOf(searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                        found = true;
                    }
                });
                if (!found) {
                    $('#noResults').show();
                } else {
                    $('#noResults').hide();
                }
            });
        });
    </script>

    <button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fas fa-arrow-up"></i></button>

    <script src="../scripts/backtotop.js?<?php echo time(); ?>"></script>

    
    <footer style="background-color: black;">
        <marquee behavior="scroll" direction="left" style="font-family: 'Arial', sans-serif; font-size: 24px; color: #ffffff; font-weight: bold;">
            <span style="color: #ff0000;">&#169; <?php echo date("Y"); ?></span> <span style="color: #1e90ff;">Alimbrary</span>
        </marquee>
    </footer>


</body>

</html>