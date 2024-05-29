<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$user_type = "user";
$signup_success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_user_type);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_user_type = $user_type;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                $signup_success_message = "Account successfully created.";
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
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="./external-css/loginstyle.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="fa-css/all.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <style>
        /* CSS for the success modal */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: auto;
            /* Auto height */
            padding: 20px;
            /* Add some padding */
            box-sizing: border-box;
            /* Include padding in width */
            text-align: center;
            /* Center the content horizontally */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            /* Display inline to take only necessary width */
            max-width: 80%;
            /* Limit the maximum width */
        }

        /* Close Button */
        .close {
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* First Button (Proceed to Login) */
        .modal-button-proceed {
            background-color: #4caf50;
            /* Green */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .modal-button-proceed:hover {
            background-color: #45a049;
        }

        /* Second Button (Create Another Account) */
        .modal-button-create {
            background-color: #f44336;
            /* Red */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .modal-button-create:hover {
            background-color: #f22f2f;
        }
    </style>
</head>

<body>
    <div class="bg-img">
        <div class="content">
            <header>Signup</header>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="field">
                    <span class="fa fa-user"></span>
                    <input type="text" name="username" required placeholder="Username" style="padding: 6px 12px;">
                </div>
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" class="pass-key" name="password" required placeholder="Password" style="padding: 6px 12px" ; id="password">
                    <span class="password-toggle-icon"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" class="pass-key" name="confirm_password" required placeholder="Confirm Password" style="padding: 6px 12px;" id="confirm_password">
                    <span class="password-toggle-icon"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="field space">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>

            <div class="signup">
                Have an account?
                <a href="login.php">Back to Login</a>
            </div>
        </div>
    </div>

    <!-- Modal for Success Message -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Your account has been successfully created.</h3>
            <button class="modal-button modal-button-proceed" onclick="proceedToLogin()">Proceed to Login</button>
            <button class="modal-button modal-button-create" onclick="createAnotherAccount()">Create Another Account</button>
        </div>
    </div>

    <script>
        // Function to display the success modal
        function displaySuccessModal() {
            var modal = document.getElementById("successModal");
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            var modal = document.getElementById("successModal");
            modal.style.display = "none";
        }

        // Function to redirect to login page
        function proceedToLogin() {
            window.location.href = "login.php";
        }

        // Function to reset the form for creating another account
        function createAnotherAccount() {
            document.querySelector("form").reset();
            closeModal();
        }

        // PHP code for displaying success message as a modal
        <?php if (!empty($signup_success_message)) : ?>
            // Show success modal on page load
            displaySuccessModal();
        <?php endif; ?>
    </script>

    <script>
        const passwordFields = document.querySelectorAll(".pass-key");
        const toggleIcons = document.querySelectorAll(".password-toggle-icon i");

        toggleIcons.forEach((icon, index) => {
            icon.addEventListener("click", function() {
                const passwordField = passwordFields[index];
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.add("fa-eye");
                    icon.classList.remove("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    icon.classList.add("fa-eye-slash");
                    icon.classList.remove("fa-eye");
                }
            });
        });

        // PHP code for displaying errors and success message
        <?php if (!empty($signup_success_message)) : ?>

        <?php endif; ?>
        <?php if (!empty($username_err) || !empty($password_err) || !empty($confirm_password_err)) : ?>
            var errorMessage = "<?php echo $username_err . $confirm_password_err; ?>";
            alert(errorMessage);
        <?php endif; ?>
    </script>

</body>

</html>