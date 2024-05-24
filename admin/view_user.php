<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../fa-css/all.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <script defer src="../js/bootstrap.bundle.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 50px;
            margin: 0;
        }

        .container {
            max-width: 900px;
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
            padding: 10px 0;
        }

        h2 {
            margin-bottom: 10px;
            color: #007bff;
            font-weight: bold;
            font-size: 1.8rem;
            font-family: 'Arial', sans-serif;
        }

        h4 {
            margin-top: 15px;
            margin-bottom: 15px;
            color: #000;
            font-weight: bold;
            font-size: 2rem;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        p {
            margin-bottom: 5px;
            font-size: 1.1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 1.1rem;
            padding: 8px 20px;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .user-info-container {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            gap: 20px;
        }

        .user-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            flex: 1;
        }

        .info-item {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            margin-right: 10px;
            width: 150px; /* Added width to align labels */
        }

        .info-value {
            font-weight: normal;
        }

        @media (max-width: 767px) {
            .user-info-container {
                flex-direction: column;
                align-items: center;
            }

            .info-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .info-label {
                margin-right: 0;
                width: auto; /* Reset width for smaller screens */
            }
            .user-info{
                margin-top: 50px;
            }
        }
    </style>
</head>

<body>
    <?php
    // Initialize the session
    session_start();

    // Include config file
    require_once "../config.php";

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
                            echo '<img src="../' . $image_path . '" class="rounded-circle user-image" alt="User Image">';
                        } else {
                            echo '<i class="fas fa-user-circle" style="color: black; font-size:200px;"></i>';
                        }
                        ?>
                       <h4><em><?php echo $username; ?></em></h4>
                    </div>
                    
                    <div class="user-info">
                        
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-id-badge"></span> User ID:</div>
                            <div class="info-value"><em><?php echo $param_id; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-envelope"></span> Email:</div>
                            <div class="info-value"><em><?php echo $email; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-user"></span> Full Name:</div>
                            <div class="info-value"><em><?php echo $full_name; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-briefcase"></span> Occupation:</div>
                            <div class="info-value"><em><?php echo $occupation; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-map-marker-alt"></span> Address:</div>
                            <div class="info-value"><em><?php echo $address; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-phone-alt"></span> Phone Number:</div>
                            <div class="info-value"><em><?php echo $contact_number; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-user-tag"></span> User Type:</div>
                            <div class="info-value"><em><?php echo $user_type; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-calendar"></span> Date Created:</div>
                            <div class="info-value"><em><?php echo $created_at_formatted; ?></em></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><span class="fas fa-calendar-check"></span> Joined:</div>
                            <div class="info-value"><em><?php echo $registration_status; ?></em></div>
                        </div>
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
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>
