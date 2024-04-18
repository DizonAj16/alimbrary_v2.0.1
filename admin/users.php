<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["user_type"] !== "admin") {
    header("location: ../login.php");
    exit;
}
require_once "../config.php";
?>

<?php
// Include config file
require_once "../config.php";

// Fetch user's profile image path from the database
$user_id = $_SESSION["id"];
$sql = "SELECT image FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $profile_image);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script defer src="../js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../external-css//navigation.css">
    <link rel="stylesheet" href="../fa-css/all.css">
    <link rel="icon" href="../Images/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            background: url('../images/glassmorphism.jpeg');
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-user {
            border-radius: 15px;
            transition: transform 0.3s ease;
            padding: 15px;
            margin-bottom: 30px;
            max-height: 450px;
            width: 300px;
            overflow: hidden;
            transition: background-color 0.3s ease;
            border: none;
        }

        .card-user:hover {
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.3);
        }

        .card-user .card-body {
            padding: 20px;
        }

        .card-user .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            color: white;
        }

        .card-user .card-text {
            font-size: 16px;
            margin-bottom: 5px;
            color: white;
        }

        .bg-admin {
            background: linear-gradient(to bottom, rgba(135, 206, 235, 0.5), transparent);
            color: blue;
            box-shadow: 0 15px 15px rgba(0, 0, 0, 0.5);
        }

        .bg-user {
            background: linear-gradient(to bottom, rgba(0, 255, 0, 0.5), transparent);
            color: blue;
            box-shadow: 0 15px 15px rgba(0, 0, 0, 0.5);
        }

        .profile-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            cursor: pointer;
            border-radius: 50%;
        }

        #backToTopBtn:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        @media (min-width: 768px) {
            .row-cols-reverse {
                flex-direction: row-reverse;
            }
        }

        footer {
            background-color: black;
            margin-top: auto;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">

        <div class="container-fluid">
            <div class="title p-1">
                <img src="../Images/logo.png" alt="" class="logo">
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="welcomeadmin.php"><i class="fa fa-home fa-lg"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="dashboard.php"><i class="fas fa-tachometer-alt fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminbooks.php"><i class="fa fa-book fa-lg"></i> Manage Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="users.php"><i class="fa fa-users fa-lg"></i> Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="borrowhistory.php"><i class="fa fa-history fa-lg"></i> Borrow History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="returnhistory.php"><i class="fa fa-archive fa-lg"></i> Return History</a>
                    </li>
                </ul>

                <!-- Dropdown -->
                <div class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            // Display user's profile image or default user icon
                            if (!empty($profile_image)) {
                                echo '<img src="../' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="rounded-circle" style="width: 32px; height: 32px;">';
                            } else {
                                echo '<i class="fa fa-user fa-lg"></i>';
                            }
                            ?>
                            <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                            <li><a class="dropdown-item" href="../reset-password.php"><i class="fas fa-unlock"></i> Reset Password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../myprofile.php"><i class="fas fa-id-card"></i> My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Sign out</a></li>
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mb-3" style="margin-top: 100px;">
        <h1 class="text-center fw-bold text-light">Users</h1>
        <div class="d-flex justify-content-center mb-3">
            <input type="search" id="searchInput" class="form-control form-control-md rounded-4 border border-primary" placeholder="Search User" style="width:300px;" autocomplete="off">
        </div>
        <div class="d-flex flex-wrap justify-content-center" id="userCardContainer">
            <?php
            // Query to retrieve users information
            $sql = "SELECT id, username, created_at, user_type, image FROM users ORDER BY id ASC";
            // Execute the query
            $result = mysqli_query($conn, $sql);
            // Check if the query was successful
            if ($result) {
                // Check if there are any rows returned
                if (mysqli_num_rows($result) > 0) {
                    // Fetch rows and display data
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cardColorClass = $row['user_type'] === 'admin' ? 'bg-admin text-admin' : 'bg-user text-user';
                        $trashButton = $row['user_type'] === 'admin' ? '<a href="admin_info.php"><button class="btn btn-primary"><i class="fas fa-eye lg text-light"></i> My Info</button></a>' : '<button class="btn btn-danger" onclick="deleteUser(' . $row['id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
            ?>
                        <div class="card <?php echo $cardColorClass; ?> card-user m-2" style="max-width: 300px;">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <?php if (!empty($row['image'])) : ?>
                                    <img src="../<?php echo $row['image']; ?>" class="card-img-top profile-image mb-2" alt="Profile Image">
                                <?php else : ?>
                                    <i class="fas fa-user-circle text-dark mb-2" style="font-size: 200px;"></i>
                                <?php endif; ?>

                                <div>
                                    <h5 class="card-title text-center"> <?php echo $row['username']; ?></h5>
                                    <p class="card-text fw-bold"><i class="fas fa-id-badge"></i> User ID: <?php echo $row['id']; ?></p>
                                    <p class="card-text fw-bold"><i class="fas fa-clock"></i> Joined: <br> <?php echo date("g:i a, F j, Y", strtotime($row['created_at'])); ?></p>
                                    <p class="card-text fw-bold"><i class="fas fa-user-circle"></i> <?php echo ucfirst($row['user_type']); ?></p>
                                    <div class="text-center">
                                        <?php if ($row['user_type'] !== 'admin') : ?>
                                            <a href="view_user.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                                        <?php endif; ?>
                                        <?php echo $trashButton; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    // No users found
                    echo '<div class="col"><div class="alert alert-danger" role="alert">No users available.</div></div>';
                }
                // Free result set
                mysqli_free_result($result);
            } else {
                // Query execution failed
                echo '<div class="col"><div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div></div>';
            }
            // Close the connection
            mysqli_close($conn);
            ?>
        </div>
    </div>



    <script>
        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "delete_user.php?id=" + id;
            }
        }
    </script>

    <script src="../jquery/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var searchText = $(this).val().trim();
                $.ajax({
                    url: '../search/search_users.php',
                    method: 'POST',
                    data: {
                        searchText: searchText
                    },
                    success: function(response) {
                        $('#userCardContainer').html(response);
                    }
                });
            });
        });
    </script>

    <button id="backToTopBtn" title="Go to top" style="height: 50px; width:50px;"><i class="fas fa-arrow-up"></i></button>

    <script src="../scripts/backtotop.js?<?php echo time(); ?>"></script>

    <footer style="background-color: black;">
        <marquee behavior="scroll" direction="left" style="font-family: 'Arial', sans-serif; font-size: 24px; color: #ffffff; font-weight: bold;">
            <span style="color: #ff0000;">&#169; <?php echo date("Y"); ?></span> <span style="color: #1e90ff;">Alimbrary</span>
        </marquee>
    </footer>


</body>

</html>