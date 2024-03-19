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
$username = $fullName = $email = $occupation = $address = $contactNum = "";
$username_err = $fullName_err = $email_err = $occupation_err = $address_err = $contactNum_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    $username = trim($_POST["username"]);
    if (empty($username) || ctype_space($username)) {
        $username = null; // Set to null if only spaces or empty
    }

    // Validate full name
    $fullName = trim($_POST["fullName"]);
    if (empty($fullName) || ctype_space($fullName)) {
        $fullName = null; // Set to null if only spaces or empty
    }

    // Validate email
    $email = trim($_POST["email"]);
    if (empty($email) || ctype_space($email)) {
        $email = null; // Set to null if only spaces or empty
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email address format.";
    }

    // Validate occupation
    $occupation = trim($_POST["occupation"]);
    if (empty($occupation) || ctype_space($occupation)) {
        $occupation = null; // Set to null if only spaces or empty
    }

    // Validate address
    $address = trim($_POST["address"]);
    if (empty($address) || ctype_space($address)) {
        $address = null; // Set to null if only spaces or empty
    }

    // Validate contact number
    $contactNum = trim($_POST["contactNum"]);
    if (empty($contactNum) || ctype_space($contactNum)) {
        $contactNum = null; // Set to null if only spaces or empty
    }

    // Prepare an update statement
    $sql = "UPDATE users SET username = ?, full_name = ?, email = ?, occupation = ?, address = ?, contact_num = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssi", $username, $fullName, $email, $occupation, $address, $contactNum, $_SESSION["id"]);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to profile page after successful update
            header("location: myprofile.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
