<?php
// Initialize the session
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
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
            padding: 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        } 
        nav {
            font-family: 'Montserrat', sans-serif;
        }

        .navbar {
            background: linear-gradient(#87CEEB, #1E90FF);
            border-bottom: 2px solid #007bff;
            border-radius: 10px 10px 10px 10px;
        }

        .navbar-toggler {
            border-color: #fff;
        }

        .navbar-nav .nav-link {
            color: #fff;
            padding: 10px 15px;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .navbar-nav .active {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        .dropdown-menu {
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .dropdown-item {
            color: #007bff;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
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
                        <a class="nav-link" href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php"><i class="fa fa-user-circle fa-lg"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="borrowers.php"><i class="fa fa-users fa-lg"></i> Borrow History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="returnhistory.php"><i class="fa fa-address-book fa-lg"></i> Return History</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user fa-lg"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                            <li><a class="dropdown-item" href="reset-password.php">Reset Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 85px;">
        <div class="card mt-2">
            <div class="card-header">
                <h3 class="fw-bold">Borrow History</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Borrow ID</th>
                            <th>Username</th>
                            <th>Book Title</th>
                            <th>Borrow Date</th>
                            <th>Return Until</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Include config file
                        require_once "config.php";

                        // Function to show borrow history
                        function showBorrowHistory($conn)
                        {
                            // Query to retrieve all borrow history information with book title
                            $sql = "SELECT borrowed_books.borrow_id, borrowed_books.borrow_date, borrowed_books.return_date, users.id, users.username, books.title 
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
                                            <td>' . $row['borrow_id'] . '</td>
                                            <td>' . $row['username'] . '</td>
                                            <td>' . $row['title'] . '</td>
                                            <td>' . $row['borrow_date'] . '</td>
                                            <td>' . ($row['return_date'] ? $row['return_date'] : 'Not returned') . '</td>
                                        </tr>';
                                }
                            } else {
                                // No borrow history found
                                echo '<tr><td colspan="5" class="text-center">No borrow history available.</td></tr>';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
