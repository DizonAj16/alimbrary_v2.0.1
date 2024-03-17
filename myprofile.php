<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $user_type = $created_at = $image = "";
$username_err = $user_type_err = $created_at_err = "";

// Prepare a select statement
$sql = "SELECT username, user_type, created_at, image FROM users WHERE id = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $_SESSION["id"];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $username, $user_type, $created_at, $image);
            if (mysqli_stmt_fetch($stmt)) {
                // Information retrieved, no action needed
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
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image {
            text-align: center;
            margin-top: 20px;
            position: relative;
        }

        .profile-image img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #3498db;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .file-upload {
            margin-left: 150px;
            margin-top: 20px;
            text-align: center;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-upload label {
            display: inline-block;
            color: green;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }


        .file-upload input[type="submit"] {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .file-upload input[type="submit"]:hover {
            background-color: #2b85b8;
        }

        .profile-info {
            margin-bottom: 20px;
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
        }

        .info-value {
            float: left;
            width: 70%;
            color: #333;
        }

        a.btn-secondary {
            display: block;
            width: 100%;
            max-width: 200px;
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
    </style>
    <script>
            document.addEventListener("DOMContentLoaded", function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        </script>
</head>

<body>
    <div class="container">
        <h2>My Profile</h2>
        <div class="profile-image">
            <?php
            if (!empty($image)) {
                echo '<img src="' . $image . '" alt="Profile Image">';
            } else {
                echo '<img src="default.jpg" alt="Profile Image">';
            }
            ?>
        </div>
        <div class="file-upload">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="image" id="image" accept="image/*">
                <label for="image" data-bs-toggle="tooltip" data-bs-title="Upload image"><i class="fas fa-plus-circle fa-lg" style="font-size: 30px;"></i></label>
                <input type="submit" value="Submit">
            </form>
        </div>
        <div class="profile-info">
            <div class="info-row">
                <div class="info-label">Username:</div>
                <div class="info-value"><?php echo $username; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">User Type:</div>
                <div class="info-value"><?php echo $user_type; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Joined:</div>
                <div class="info-value"><?php echo $created_at; ?></div>
            </div>
        </div>
        <a href="<?php echo $_SESSION['user_type'] === 'admin' ? 'welcomeadmin.php' : 'userwelcome.php'; ?>" class="btn btn-secondary">Back</a>
    </div>
    
</body>

</html>
