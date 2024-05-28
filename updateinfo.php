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
$id = $username = $user_type = $created_at = $image = $fullName = $email = $occupation = $address = $contactNum = "";
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
    <title>Update User Info</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./fa-css/all.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
        }

        h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #3498db;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #555;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #3498db;
            color: #fff;
            border: none;
            margin-bottom: 10px;
        }

        .btn-primary:hover {
            background-color: #2b85b8;
        }

        .btn-secondary {
            background-color: #ccc;
            color: #000;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #999;
            color: #000;
        }

        .text-danger {
            color: #ff0000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Update User Info</h2>
        <form action="update_process.php" method="post">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="fullName">Full Name:</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo $fullName; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="occupation">Occupation:</label>
                    <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo $occupation; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="address">Address:</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="contactNum">Contact Number:</label>
                    <input type="text" class="form-control" id="contactNum" name="contactNum" value="<?php echo $contactNum; ?>">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" class="btn btn-primary" value="Update">
                <a class="btn btn-secondary" href="myprofile.php">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
