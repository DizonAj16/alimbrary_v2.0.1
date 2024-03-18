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
    if (!empty(trim($_POST["username"]))) {
        $username = trim($_POST["username"]);
    }

    // Validate full name
    if (!empty(trim($_POST["fullName"]))) {
        $fullName = trim($_POST["fullName"]);
    }
    
    // Validate email
    if (!empty(trim($_POST["email"]))) {
        if (filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email = trim($_POST["email"]);
        } else {
            $email_err = "Invalid email address format.";
        }
    }
    
    // Validate occupation
    if (!empty(trim($_POST["occupation"]))) {
        $occupation = trim($_POST["occupation"]);
    }
    
    // Validate address
    if (!empty(trim($_POST["address"]))) {
        $address = trim($_POST["address"]);
    }
    
    // Validate contact number
    if (!empty(trim($_POST["contactNum"]))) {
        $contactNum = trim($_POST["contactNum"]);
    }
    
    // Check if any field has been updated
    if (!empty($username) || !empty($fullName) || !empty($email) || !empty($occupation) || !empty($address) || !empty($contactNum)) {
        // Prepare an update statement
        $sql = "UPDATE users SET ";
        $params = array();
        $types = "";
        
        if (!empty($username)) {
            $sql .= "username = ?, ";
            $params[] = $username;
            $types .= "s";
        }
        if (!empty($fullName)) {
            $sql .= "full_name = ?, ";
            $params[] = $fullName;
            $types .= "s";
        }
        if (!empty($email)) {
            $sql .= "email = ?, ";
            $params[] = $email;
            $types .= "s";
        }
        if (!empty($occupation)) {
            $sql .= "occupation = ?, ";
            $params[] = $occupation;
            $types .= "s";
        }
        if (!empty($address)) {
            $sql .= "address = ?, ";
            $params[] = $address;
            $types .= "s";
        }
        if (!empty($contactNum)) {
            $sql .= "contact_num = ?, ";
            $params[] = $contactNum;
            $types .= "s";
        }
        
        // Remove the trailing comma and space
        $sql = rtrim($sql, ", ");
        
        $sql .= " WHERE id = ?";
        $types .= "i";
        
        $params[] = $_SESSION["id"];
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            
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
    } else {
        // No fields to update
        header("location: myprofile.php");
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
