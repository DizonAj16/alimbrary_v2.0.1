<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fa-css/all.css">
    <script defer src="js/bootstrap.bundle.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 50px;
            margin: 0;
        }

        .container {
            max-width: 650px;
            margin: auto;
            padding: 0 15px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }

        h2 {
            margin-bottom: 0;
            color: #007bff;
            font-weight: bold;
            font-size: 1.8rem;
            font-family: 'Arial', sans-serif;
        }

        h4 {
            margin-bottom: 15px;
            color: #000;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        p {
            margin-bottom: 8px;
            font-size: 1.1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 1.1rem;
            padding: 8px 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .user-info-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
        }

        .user-info {
            text-align: start;
            max-width: 500px;
        }

        .user-image {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
<?php
        // Initialize the session
        session_start();

        // Include config file
        require_once "config.php";

        // Check if user_id parameter is provided in the URL
        if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            // Prepare a select statement
            $sql = "SELECT id, username, email, full_name, occupation, address, contact_num, user_type, created_at, image FROM users WHERE id = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);

                // Set parameters
                $param_id = trim($_GET["id"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 1) {
                        // Fetch result row
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        // Retrieve individual field values
                        $username = $row["username"];
                        $email = $row["email"];
                        $full_name = $row["full_name"];
                        $occupation = $row["occupation"];
                        $address = $row["address"];
                        $contact_number = $row["contact_num"];
                        $user_type = $row["user_type"];
                        $created_at = $row["created_at"];
                        $created_at_formatted = date("F j, Y, g:i A", strtotime($created_at));
                        $image_path = $row["image"];

                        $registration_date = new DateTime($created_at);
                        $current_date = new DateTime();
                        $interval = $current_date->diff($registration_date);
                        $days_since_registration = $interval->days;

                        // If user joined today, display "Just now"
                        if ($days_since_registration == 0) {
                            $registration_status = "Just now";
                        } else {
                            // If user joined within the last 30 days, display the number of days
                            if ($days_since_registration <= 30) {
                                $registration_status = "$days_since_registration day(s) ago";
                            } else {
                                // If user joined more than 30 days ago, calculate months and remaining days
                                $months = floor($days_since_registration / 30);
                                $remaining_days = $days_since_registration % 30;
                                if ($remaining_days == 0) {
                                    $registration_status = "$months month(s) ago";
                                } else {
                                    $registration_status = "$months month(s) $remaining_days day(s) ago";
                                }
                            }
                        }

                        // Close the statement
                        mysqli_stmt_close($stmt);

                        // Close the connection
                        mysqli_close($conn);
                    } else {
                        // Redirect to error page if user ID doesn't exist
                        header("location: error.php");
                        exit();
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        } else {
            // Redirect to error page if user ID parameter is not provided
            header("location: error.php");
            exit();
        }
        ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="text-light">User Information</h2>
            </div>
            <div class="card-body">
                <div class="user-info-container">
                    <div class="user-image">
                        <?php
                        if (!empty($image_path)) {
                            echo '<img src="' . $image_path . '" class="rounded-circle user-image" alt="User Image">';
                        } else {
                            echo '<i class="fas fa-user-circle" style="color: black; font-size:200px;"></i>';
                        }
                        ?>
                    </div>

                    <div class="user-info">
                        <h4><?php echo $username; ?></h4>
                        <p><strong>User ID: </strong><?php echo $param_id; ?></p>
                        <p><strong>Email: </strong><?php echo $email; ?></p>
                        <p><strong>Full Name: </strong><?php echo $full_name; ?></p>
                        <p><strong>Occupation: </strong><?php echo $occupation; ?></p>
                        <p><strong>Address: </strong><?php echo $address; ?></p>
                        <p><strong>Contact Number: </strong><?php echo $contact_number; ?></p>
                        <p><strong>User Type: </strong><?php echo $user_type; ?></p>
                        <p><strong>Date Created: </strong><?php echo $created_at_formatted; ?></p>
                        <p><strong>Joined: </strong><?php echo $registration_status; ?></p> 
                        <a href="users.php" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-title="Back to Users"><i class="fas fa-chevron-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new
                bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>

</html>

