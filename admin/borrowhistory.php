<?php
// Initialize the session
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
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
    <title>Borrow History</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script defer src="../js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../external-css/navigation.css">
    <link rel="stylesheet" href="../fa-css/all.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
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

        .table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: #fff;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
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
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title p-1">
                <img src="../Images/logo.png" alt="" class="logo">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="dashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="users.php"><i class="fa fa-users fa-lg"></i> Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="borrowhistory.php"><i class="fa fa-history fa-lg"></i> Borrow History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="returnhistory.php"><i class="fa fa-archive fa-lg"></i> Return History</a>
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


    <div class="container" style="margin-top: 95px;">
        <div class="card mt-2">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">Borrow History</h3>
                <div class="input-group" style="max-width: 200px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by username...">
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-responsive table-hover mb-0" id="borrowHistoryTable">
                        <thead class="text-center">
                            <tr>
                                <th>Borrow ID</th>
                                <th>Username</th>
                                <th>Book Title</th>
                                <th>Borrow Date</th>
                                <th>Return Until</th>
                                <th>Time Left</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include config file
                            require_once "../config.php";

                            // Function to show borrow history
                            function showBorrowHistory($conn)
                            {
                                // Query to retrieve all borrow history information with book title and status
                                $sql = "SELECT borrowed_books.borrow_id, borrowed_books.borrow_date, borrowed_books.return_date, borrowed_books.return_date, users.id, users.username, books.title, return_history.return_id 
                                FROM borrowed_books 
                                INNER JOIN users ON borrowed_books.user_id = users.id
                                INNER JOIN books ON borrowed_books.book_id = books.book_id
                                LEFT JOIN return_history ON borrowed_books.borrow_id = return_history.borrow_id
                                ORDER BY borrowed_books.borrow_id DESC";

                                // Execute the query
                                $result = mysqli_query($conn, $sql);

                                // Check if the query was successful
                                if ($result && mysqli_num_rows($result) > 0) {
                                    // Display borrow history information in a table
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>
                                        <td class="text-center">' . $row['borrow_id'] . '</td>
                                        <td class="text-center">' . $row['username'] . '</td>
                                        <td class="fw-bold text-start">' . $row['title'] . '</td>
                                        <td class="text-center">' . date("F j, Y, h:i A", strtotime($row['borrow_date'])) . '</td>
                                        <td class="text-center">' . ($row['return_date'] ? date("F j, Y, h:i A", strtotime($row['return_date'])) : 'Not returned') . '</td>';

                                        // If not returned, calculate time left
                                        echo '<td>';
                                        if ($row['return_id']) {
                                            // If returned, display a success badge and leave the time left column blank

                                        } else {
                                            // If not returned, calculate time left
                                            $current_date = time() + (6 * 60 * 60); // Adding 6 hours to the current timestamp to adjust for the discrepancy
                                            $return_date = strtotime($row['return_date']); // Return timestamp
                                            $time_left = $return_date - $current_date; // Time left in seconds

                                            $days = floor($time_left / (60 * 60 * 24));
                                            $hours = floor(($time_left % (60 * 60 * 24)) / (60 * 60));
                                            $minutes = floor(($time_left % (60 * 60)) / 60);

                                            if ($days < 0) {
                                                echo '<p class="text-danger">Overdue</p>';
                                            } elseif ($days == 0 && $hours < 24) {
                                                echo '<p class="text-danger">Book nearing due: ' . $hours . ' hour(s), ' . $minutes . ' minute(s)</p>';
                                            } else {
                                                echo $days . ' day(s), ' . $hours . ' hour(s), ' . $minutes . ' minute(s)';
                                            }
                                        }
                                        echo '</td>';



                                        // Display status
                                        echo '<td class="text-center">';
                                        if ($row['return_id']) {
                                            echo '<span class="badge bg-success">Returned</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Not returned</span>';
                                        }
                                        echo '</td>';

                                        echo '</tr>';
                                    }
                                } else {
                                    // No borrow history found
                                    echo '<tr><td colspan="7" class="text-center">No borrow history available.</td></tr>';
                                }
                                // Free result set
                                mysqli_free_result($result);
                            }

                            // Call the function to display borrow history
                            showBorrowHistory($conn);

                            // Close the connection
                            mysqli_close($conn);
                            ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Function to perform live search
        function liveSearch() {
            // Get the search query from the input field
            var searchQuery = document.getElementById('searchInput').value.trim();

            // Send the search query to the server using AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the table with the filtered results
                    document.getElementById("borrowHistoryTable").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "../search/search_borrow_history.php?q=" + searchQuery, true);
            xhttp.send();
        }

        // Trigger live search on input change
        document.getElementById('searchInput').addEventListener('input', liveSearch);
    </script>

    <button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fas fa-arrow-up"></i></button>
    <script src="../jquery/jquery-3.5.1.min.js"></script>
    <script src="../scripts/backtotop.js?<?php echo time(); ?>"></script>

</body>

</html>