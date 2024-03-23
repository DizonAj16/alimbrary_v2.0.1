<?php
// Start session
session_start();

// Check if the user is logged in and is an admin, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "user") {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Get the user ID of the currently logged-in user
$user_id = $_SESSION["id"];

// Define SQL query to fetch return history data for the logged-in user
$return_history_sql = "SELECT return_history.return_id, borrowed_books.borrow_date, users.username, books.title, return_history.returned_date_time, borrowed_books.return_date
                        FROM return_history
                        JOIN users ON return_history.user_id = users.id
                        JOIN books ON return_history.book_id = books.book_id
                        JOIN borrowed_books ON return_history.borrow_id = borrowed_books.borrow_id
                        WHERE users.id = ?
                        ORDER BY return_history.returned_date_time DESC";

// Prepare the query
if ($stmt = mysqli_prepare($conn, $return_history_sql)) {
    // Bind the user ID parameter
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>

<?php
// Include config file
require_once "config.php";

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
    <title>Return History</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navigation.css">
    <link rel="stylesheet" href="fa-css/all.css">
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

        table {
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

        .card-header {
            background-color: #007bff !important;
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
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
                <img class="logo" src="Images/logo.png" alt="">
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
                    <a class="nav-link" href="userdashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userbook.php"><i class="fa fa-book fa-lg"></i> Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowedbooks.php"><i class="fas fa-book-reader fa-lg"></i> Borrowed Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="userreturnhistory.php"><i class="fa fa-book fa-lg"></i> Returned Books</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            // Display user 's profile image or default user icon
                            if (!empty($profile_image)) {
                                echo '<img src="' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="rounded-circle" style="width: 32px; height: 32px;">';
                            } else {
                                echo '<i class="fa fa-user fa-lg"></i>';
                            }
                            ?>
                            <?php echo htmlspecialchars($_SESSION["username"]); ?>
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

    <div class="container" style="margin-top:85px">
        <?php if (mysqli_num_rows($result) === 0) : ?>
            <div class='alert alert-danger' role='alert'>
                <h4 class='alert-heading'>No Returned Books</h4>
                <p class='mb-0'>You haven't returned any books yet.</p>
            </div>
        <?php else : ?>
            <div class="card mt-2">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold mb-0">Return History</h2>
                    <div class="d-flex">
                        <input type="text" id="searchInput" class="form-control me-2" placeholder="Search by Book Title..." style="width: 200px;">
                        <!-- You can add a search button here if needed -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Return ID</th>
                                    <th>Title</th>
                                    <th>Borrow Date</th>
                                    <th>Date Returned</th>
                                    <th>Days Borrowed</th>
                                    <th>Return Status</th> <!-- New column for return status -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Loop through each row in the result set
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['return_id'] . "</td>";
                                    echo "<td class='fw-bold'>" . $row['title'] . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row['borrow_date'])) . "</td>";
                                    echo "<td>" . date("F j, Y, h:i A", strtotime($row['returned_date_time'])) . "</td>";

                                    // Calculate the number of days borrowed
                                    $borrow_date = strtotime($row['borrow_date']);
                                    $returned_date = strtotime($row['returned_date_time']);
                                    $days_borrowed = floor(($returned_date - $borrow_date) / (60 * 60 * 24));

                                    // If days borrowed exceeds 30 days, convert it into months and remaining days
                                    if ($days_borrowed > 30) {
                                        $months = floor($days_borrowed / 30);
                                        $remaining_days = $days_borrowed % 30;
                                        if ($remaining_days == 0) {
                                            echo "<td>$months month(s)</td>";
                                        } else {
                                            echo "<td>$months month(s) $remaining_days day(s)</td>";
                                        }
                                    } else {
                                        // If days borrowed is less than or equal to 30 days, display the number of days
                                        if ($days_borrowed == 0) {
                                            echo "<td>Less than a day</td>";
                                        } else {
                                            echo "<td>$days_borrowed day(s)</td>";
                                        }
                                    }

                                    // Determine return status
                                    $expected_return_date = strtotime($row['return_date']);
                                    
                                    if ($returned_date <= $expected_return_date) {
                                        echo '<td><span class="badge bg-success">On Time</span></td>';
                                    } else {
                                        echo '<td><span class="badge bg-danger">Late</span></td>';
                                    }

                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="noResults" class="alert alert-danger mt-3 mb-3 me-2 ms-2" style="display: none;">
                        No results found
                    </div>
                </div>
            </div>
    </div>
<?php endif; ?>



<script src="jquery/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#searchInput').keyup(function() {
            var searchText = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                var title = $(this).find('td:eq(1)').text().toLowerCase(); // Index 1 for the title column
                if (title.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            if ($('tbody tr:visible').length === 0) {
                $('#noResults').show();
            } else {
                $('#noResults').hide();
            }
        });
    });
</script>


<button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fas fa-arrow-up"></i></button>
<script src="jquery/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#backToTopBtn').fadeIn();
            } else {
                $('#backToTopBtn').fadeOut();
            }
        });


        $('#backToTopBtn').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            return false;
        });
    });
</script>

</body>

</html>

<?php
// Close connection
mysqli_close($conn);
?>

