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
            font-family: Arial, sans-serif;
            padding-top: 50px;
            /* Add padding to the top */
        }

        .container {
            max-width: 850px;
            /* Limit maximum width of the container */
            margin: 0 auto;
            /* Center the container horizontally */
            padding: 0 15px;
            /* Add padding to the sides */
            margin-bottom: 40px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        h4 {
            margin-bottom: 10px;
            color: #007bff;
            font-weight: bold;
            font-size: 1.5rem;
        }

        p {
            margin-bottom: 8px;
            font-size: 1.1rem;
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
            align-items: center;
            justify-content: space-evenly;
        }

        .user-info {
            flex: 1;
            margin-left: 30px;
        }

        .user-image {
            width: 150px;
            /* Adjust the width of the user image */
            height: 150px;
            /* Adjust the height of the user image */
            margin-bottom: 20px;
            object-fit: cover;
            /* Maintain aspect ratio and cover container */
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .user-info-container {
                flex-direction: column;
            }

            .user-info {
                margin-left: 0;
            }
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
                    $image_path = $row["image"];

                    // Calculate time since registration
                    // Calculate time since registration
                    $registration_date = new DateTime($created_at);
                    $current_date = new DateTime();
                    $interval = $current_date->diff($registration_date);
                    $days_since_registration = $interval->days;

                    // If user joined today, display "Just now"
                    if ($days_since_registration == 0) {
                        $registration_status = "Just now";
                    } else {
                        // If user joined more than 30 days ago, display duration in terms of months and days
                        if ($days_since_registration > 30) {
                            $months = floor($days_since_registration / 30);
                            $remaining_days = $days_since_registration % 30;
                            if ($remaining_days == 0) {
                                $registration_status = "$months month(s)";
                            } else {
                                $registration_status = "$months month(s) $remaining_days day(s)";
                            }
                        } else {
                            // If user joined within the last 30 days, display the number of days
                            $registration_status = "$days_since_registration day(s) ago";
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
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>User Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="user-info-container">
                            <div class="user-image">
                                <?php
                                echo '<img src="' . $image_path . '" class="rounded-circle user-image" alt="User Image" style="border: 1px solid blue;">';
                                ?>
                            </div>

                            <div class="user-info">
                                <h4><?php echo $username; ?></h4>
                                <p><strong>User ID:</strong> <?php echo $param_id; ?></p>
                                <p><strong>Email:</strong> <?php echo $email; ?></p>
                                <p><strong>Full Name:</strong> <?php echo $full_name; ?></p>
                                <p><strong>Occupation:</strong> <?php echo $occupation; ?></p>
                                <p><strong>Address:</strong> <?php echo $address; ?></p>
                                <p><strong>Contact Number:</strong> <?php echo $contact_number; ?></p>
                                <p><strong>User Type:</strong> <?php echo $user_type; ?></p>
                                <p><strong>Date Created:</strong> <?php echo $created_at; ?></p>
                                <p><strong>Joined <?php echo $days_since_registration; ?> days ago</strong></p>
                                <div class="text-center"></div>
                                <a href="users.php" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Back to Users</a>
                            </div>
                        </div>
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