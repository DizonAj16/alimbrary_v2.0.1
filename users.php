<?php
// Include config file
require_once "config.php";

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
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
    <title>Users Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa;
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
            color: #fff; /* Icon color is set to white */
        }

        .card-user .card-body h5,
        .card-user .card-body p {
            color: #fff; /* Text color is set to white */
            margin: 0;
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
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-end">
                        <h3 class="text-center">Users Dashboard</h3>
                        <a href="welcomeadmin.php" class="btn btn-primary text-light"><i class="fa fa-home"></i> Back to Home</a>
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
</body>

</html>
