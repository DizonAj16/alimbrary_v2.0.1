<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password, user_type FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $user_type);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["user_type"] = $user_type;

                            // Redirect user based on user type
                            if ($user_type == "admin") {
                                header("location: ./admin/welcomeadmin.php");
                            } else {
                                header("location: ./user/userwelcome.php");
                            }
                        } else {
                            // Password is not valid, set login error
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, set login error
                    $login_err = "Invalid username or password.";
                }
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
        <title>Login</title>      
        <link rel="stylesheet" href="fa-css/all.css">
        <link rel="stylesheet" href="./external-css/loginstyle.css">
        <style>
            @media only screen and (max-width: 480px) {
                .content {
                    width: 80%;
                }
                .field {
                    margin-bottom: 20px;
                }
            }
        </style>
   </head>
   <body>
      <div class="bg-img">
         <div class="content">
            <header>Login</header>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="text" name="username" required placeholder="Username" style="padding: 6px 12px;">
               </div>
               <div class="field space">
                  <span class="fa fa-lock"></span>
                  <input type="password" class="pass-key" name="password" required placeholder="Password" style="padding: 6px 12px;" id="password">
               </div>
               <div class="pass">
                  <a href="forgotpassword.php">Forgot Password?</a>
               </div>
               <div class="field">
                  <input type="submit" value="LOGIN">
               </div>
            </form>
            <script>
                <?php if (!empty($login_err)) : ?>
                    // Display login error as an alert
                    alert("<?php echo $login_err; ?>");
                <?php endif; ?>
            </script>
            <div class="signup">
               Don't have an account?
               <a href="register.php">Signup Now</a>
            </div>
         </div>
      </div>
   </body>
</html>
