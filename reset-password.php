<?php
// Include config file
require_once "config.php";

$current_password = $new_password = $confirm_password = "";
$current_password_err = $new_password_err = $confirm_password_err = "";

session_start();
$cancel_link = isset($_SESSION["user_type"]) && $_SESSION["user_type"] == 'admin' ? "./admin/welcomeadmin.php" : "./user/userwelcome.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Current password
    if (empty(trim($_POST["current_password"]))) {
        $current_password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // New password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate current password
    if (empty($current_password_err)) {
        $sql = "SELECT password FROM users WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (!password_verify($current_password, $hashed_password)) {
                            $current_password_err = "The current password is incorrect.";
                        }
                    }
                } else {
                    $current_password_err = "User not found.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Check input errors before updating the database
    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully. Redirect to login page
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./fa-css/all.css">
    <link rel="stylesheet" href="./external-css/loginstyle.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <script>
        function showAlertAndRedirect() {
            <?php if (!empty($current_password_err) && (empty($new_password_err) && empty($confirm_password_err))) : ?>
                alert("Error changing password: <?php echo $current_password_err; ?>");
                return false;
            <?php elseif (empty($current_password_err) && ((!empty($new_password_err) || !empty($confirm_password_err)))) : ?>
                alert("Error changing password: Please check the new password and confirm password fields.");
                return false;
            <?php else : ?>
                alert("Password changed successfully.");
                clearErrors(); // Clear all errors
                return true;
            <?php endif; ?>
        }

        function clearErrors() {
            var errorElements = document.getElementsByClassName("error");
            for (var i = 0; i < errorElements.length; i++) {
                errorElements[i].innerHTML = "";
            }
        }
    </script>
</head>

<body>
    <div class="bg-img">
        <div class="content">
            <header>Reset Password</header>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="field">
                    <span class="fa fa-lock"></span>
                    <input type="password" class="form-control pass-key" name="current_password" required placeholder="Current Password">
                    <span class="password-toggle-icon" onclick="togglePasswordVisibility(this)"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" class="form-control pass-key" name="new_password" required placeholder="New Password">
                    <span class="password-toggle-icon" onclick="togglePasswordVisibility(this)"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" class="form-control pass-key" name="confirm_password" required placeholder="Confirm Password">
                    <span class="password-toggle-icon" onclick="togglePasswordVisibility(this)"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="pass">
                    <a href=""> </a>
                </div>
                <div class="field space">
                    <input type="submit" value="Submit" onclick="return showAlertAndRedirect()">
                    
                </div>
                <a class="btn-link" href="<?php echo $cancel_link; ?>">Cancel</a>
            </form>
            <?php if (!empty($current_password_err) || !empty($new_password_err) || !empty($confirm_password_err)) : ?>
                <div class="error">
                    <?php echo $current_password_err; ?>
                    <?php echo $new_password_err; ?>
                    <?php echo $confirm_password_err; ?>
                </div>
            <?php endif; ?>
                
        </div>
    </div>

    <script>
        function togglePasswordVisibility(icon) {
            const passwordField = icon.previousElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.innerHTML = '<i class="fas fa-eye"></i>';
              
            } else {
                passwordField.type = "password";
                icon.innerHTML = '<i class="fas fa-eye-slash"></i>';
            }
        }
    </script>
</body>

</html>
