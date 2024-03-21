<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$id = $username = $user_type = $created_at = $image = $fullName = $email = $occupation = $address = $contactNum = $daysJoined = "";
$username_err = $user_type_err = $created_at_err = "";

// Prepare a select statement
$sql = "SELECT id, username, full_name, email, occupation, address, contact_num, user_type, created_at, image FROM users WHERE id = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $_SESSION["id"];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $fullName, $email, $occupation, $address, $contactNum, $user_type, $created_at, $image);
            if (mysqli_stmt_fetch($stmt)) {
                // Calculate days joined
                // Calculate days joined
                $dateJoined = new DateTime($created_at);
                $dateNow = new DateTime();
                $interval = $dateJoined->diff($dateNow);
                $daysJoined = $interval->format('%a');

                // If days joined exceeds 30 days, convert it into months and remaining days
                if ($daysJoined > 30) {
                    $months = floor($daysJoined / 30);
                    $remainingDays = $daysJoined % 30;
                    if ($remainingDays == 0) {
                        $daysJoined = "$months month(s)";
                    } else {
                        $daysJoined = "$months month(s) $remainingDays ";
                    }
                } else {
                    // If days joined is less than or equal to 30 days, display the number of days
                    $daysJoined = "$daysJoined day(s)";
                }

                if ($daysJoined == 0) {
                    $daysJoined = "Just now";
                } else {
                    $daysJoined .= " day(s) ago";
                }
            }
        } else {
            // No rows found, something went wrong
            $user_type_err = "User not found.";
        }
    } else {
        // Error executing statement
        $created_at_err = "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fa-css/all.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.308);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: transparent;
            border-radius: 8px;
            box-shadow: 0 15px 15px rgba(0, 0, 0, 0.5);
        }

        h2 {
            font-weight: bold;
            font-size: 28px;
            color: black;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #3498db;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto;
        }

        .profile-info,
        .profile-image {
            background-color: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            margin-bottom: 10px;
            overflow: hidden;
        }

        .info-label {
            float: left;
            width: 30%;
            color: #555;
            font-weight: bold;
            text-align: left;
            /* Adjusted text alignment */
            margin-top: 5px;
            /* Added margin to the top */
        }

        .info-value {
            float: left;
            width: 70%;
            color: #333;
            font-weight: normal;
            text-align: left;
            /* Adjusted text alignment */
            margin-top: 5px;
            /* Added margin to the top */
        }

        .file-upload {
            text-align: center;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #3498db;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto;
        }

        input[type="file"] {
            display: none;
        }

        label[for="image"] {
            cursor: pointer;
        }

        a.btn-secondary {
            display: block;
            width: 100%;
            max-width: 50px;
            margin: 20px auto 0;
            padding: 10px 10px;
            text-align: center;
            background-color: #3498db;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a.btn-secondary:hover {
            background-color: #2b85b8;
        }

        @media (max-width: 767px) {

            .info-label,
            .info-value {
                width: 100%;
                float: none;
                text-align: center;
                /* Center align on smaller screens */
            }

            .container {
                max-width: 90%;
                padding: 20px;
            }
        }

        .no-underline {
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="fw-bold">My Profile</h2>
        <div class="profile-image">
            <?php
            if (!empty($image)) {
                echo '<img src="' . $image . '" alt="Profile Image">';
            } else {
                echo '<img src="default.jpg" alt="Profile Image">';
            }
            ?>
            <div class="file-upload mt-2">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image" id="image" accept="image/*">
                    <label for="image" data-bs-toggle="tooltip" data-bs-title="Upload image"><i class="fas fa-plus-circle fa-lg text-success" style="font-size: 30px;"></i></label>
                    <input type="submit" class="btn btn-link no-underline" value="Submit">
                </form>
            </div>
        </div>

        <div class="profile-info">
            <div class="info-row">
                <div class="info-label">ID:</div>
                <div class="info-value"><?php echo $id; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Username:</div>
                <div class="info-value"><?php echo $username; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value"><?php echo $fullName; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?php echo $email; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Occupation:</div>
                <div class="info-value"><?php echo $occupation; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value"><?php echo $address; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Contact Number:</div>
                <div class="info-value"><?php echo $contactNum; ?></div>
            </div>

            <div class="info-row">
                <div class="info-label">User Type:</div>
                <div class="info-value"><?php echo $user_type; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Date created:</div>
                <div class="info-value"><?php echo $created_at; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Joined </div>
                <div class="info-value"><?php echo $daysJoined; ?></div>
            </div>
            <a class="btn btn-link text-info no-underline" href="updateinfo.php">Update User Info</a>
        </div>
        <a href="users.php" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-title="Back to Users"><i class="fas fa-chevron-left"></i></a>

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>

</html>