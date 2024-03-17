<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: login.php");
    exit;
}
require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="titlestyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="navstyle.css">
    <link rel="stylesheet" href="fa-css/all.css">
    <style>
        .card-user {
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .card-user:hover {
            transform: translateY(-5px);
            cursor: pointer;
        }

        .card-user .card-body {
            padding: 20px;
        }

        .card-user .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-user .card-text {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title rounded-3 p-1">
                <span class="letter-a">A</span>
                <span class="letter-l">l</span>
                <span class="letter-i">i</span>
                <span class="letter-m">m</span>
                <span class="letter-b">b</span>
                <span class="letter-r">r</span>
                <span class="letter-a">a</span>
                <span class="letter-r">r</span>
                <span class="letter-y">y</span>
                <img src="Images/icons8-book-50.png" alt="" style="margin-left: 5px;">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-info-circle fa-lg"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="users.php"><i class="fa fa-user-circle fa-lg"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowhistory.php"><i class="fa fa-users fa-lg"></i> Borrow History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="returnhistory.php"><i class="fa fa-address-book fa-lg"></i> Return History</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user fa-lg"></i> <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                            <li><a class="dropdown-item" href="reset-password.php"><i class="fas fa-undo"></i> Reset Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            // Query to retrieve users information
            $sql = "SELECT id, username, created_at, user_type, image FROM users";

            // Execute the query
            $result = mysqli_query($conn, $sql);

            // Check if the query was successful
            if ($result) {
                // Check if there are any rows returned
                if (mysqli_num_rows($result) > 0) {
                    // Fetch rows and display data
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cardColorClass = $row['user_type'] === 'admin' ? 'bg-admin text-admin' : 'bg-user text-user';
            ?>
                        <div class="col">
                            <div class="card <?php echo $cardColorClass; ?> card-user shadow">
                                <div class="card-body d-flex align-items-center">
                                    <?php if (!empty($row['image'])) : ?>
                                        <img src="<?php echo $row['image']; ?>" class="profile-image" alt="Profile Image">
                                    <?php else : ?>
                                        <div class="icon"><i class="<?php echo $iconClass; ?>"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <h5 class="card-title"><i class="fas fa-user"></i> <?php echo $row['username']; ?></h5>
                                        <p class="card-text"><i class="fas fa-id-badge"></i> User ID: <?php echo $row['id']; ?></p>
                                        <p class="card-text"><i class="fas fa-clock"></i> Joined: <?php echo $row['created_at']; ?></p>
                                        <p class="card-text"><i class="<?php echo $iconClass; ?>"></i> <?php echo ucfirst($row['user_type']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    // No users found
                    echo '<div class="col">
                        <div class="alert alert-danger" role="alert">No users available.</div>
                      </div>';
                }
                // Free result set
                mysqli_free_result($result);
            } else {
                // Query execution failed
                echo '<div class="col">
                    <div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div>
                  </div>';
            }
            // Close the connection
            mysqli_close($conn);
            ?>
        </div>
    </div>



</body>

</html>