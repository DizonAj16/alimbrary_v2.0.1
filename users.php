<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;

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

        .card-user {
            margin-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-user .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-radius: 15px 15px 0 0;
        }

        .card-user .card-body {
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .card-user .icon {
            font-size: 50px;
            margin-right: 20px;
            color: #fff;
            /* Icon color is set to white */
        }

        .card-user .card-body h5,
        .card-user .card-body p {
            color: #fff;
            /* Text color is set to white */
            margin: 0;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .bg-admin {
            background-color: #007bff;
        }

        .bg-user {
            background-color: #28a745;
        }

        .text-admin {
            color: #007bff;
        }

        .text-user {
            color: #28a745;
        }

        .btn-back {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
        }

        /* Additional styles for improved aesthetics */
        .container {
            border-radius: 15px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h3 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        p.text-light {
            color: #fff;
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
                        <a class="nav-link active" aria-current="page" href="users.php"><i class="fa fa-user-circle fa-lg"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowers.php"><i class="fa fa-users fa-lg"></i> Borrow History</a>
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

    <div class="container mb-5" style="margin-top: 70px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-end">
                        <h3 class="text-light">Users Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            // Query to retrieve users information
                            $sql = "SELECT id, username, created_at, user_type FROM users";

                            // Execute the query
                            $result = mysqli_query($conn, $sql);

                            // Check if the query was successful
                            if ($result) {
                                // Check if there are any rows returned
                                if (mysqli_num_rows($result) > 0) {
                                    // Fetch rows and display data
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $cardColorClass = $row['user_type'] === 'admin' ? 'bg-admin text-admin' : 'bg-user text-user';
                                        $iconClass = $row['user_type'] === 'admin' ? 'fa fa-user-circle' : 'fa fa-user';
                                        echo '<div class="col-md-4">
                                            <div class="card ' . $cardColorClass . ' card-user">
                                                <div class="card-body">
                                                    <div class="icon"><i class="' . $iconClass . '"></i></div>
                                                    <div>
                                                        <h5><i class="fa fa-user"></i> ' . $row['username'] . '</h5>
                                                        <p><i class="fa fa-id-badge"></i> User ID: ' . $row['id'] . '</p>
                                                        <p><i class="fa fa-clock-o"></i> Joined: ' . $row['created_at'] . '</p>
                                                        <p><i class="' . $iconClass . '"></i> ' . ucfirst($row['user_type']) . '</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                } else {
                                    // No users found
                                    echo '<p class="text-center text-light">No users available.</p>';
                                }
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                // Query execution failed
                                echo '<p class="text-center text-light">Error: ' . mysqli_error($conn) . '</p>';
                            }

                            // Close the connection
                            mysqli_close($conn);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>