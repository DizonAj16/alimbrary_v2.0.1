<?php
// Include config file
require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process search input
    $searchText = trim($_POST["searchText"]);

    // Prepare a SELECT statement
    $sql = "SELECT id, username, created_at, user_type, image FROM users WHERE username LIKE ? ORDER BY id ASC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_search);

        // Set parameters
        $param_search = '%' . $searchText . '%';

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if any users match the search query
            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $created_at, $user_type, $image);

                // Fetch users and display cards
                while (mysqli_stmt_fetch($stmt)) {
                    $cardColorClass = $user_type === 'admin' ? 'bg-admin text-admin' : 'bg-user text-user';
                    $trashButton = $user_type === 'admin' ? '<a href="admin_info.php"><button class="btn btn-primary"><i class="fas fa-eye lg text-light"></i> My Info</button></a>' : '<button class="btn btn-danger" onclick="deleteUser(' . $id . ')"><i class="fas fa-trash"></i> Delete</button>';
            ?>
                    <div class="card <?php echo $cardColorClass; ?> card-user m-2" style="max-width: 300px;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <?php if (!empty($image)) : ?>
                                <img src="../<?php echo $image; ?>" class="card-img-top profile-image mb-2" alt="Profile Image">
                            <?php else : ?>
                                <i class="fas fa-user-circle text-dark mb-2" style="font-size: 200px;"></i>
                            <?php endif; ?>

                            <div>
                                <h5 class="card-title text-center"> <?php echo $username; ?></h5>
                                <p class="card-text fw-bold"><i class="fas fa-id-badge"></i> User ID: <?php echo $id; ?></p>
                                <p class="card-text fw-bold"><i class="fas fa-clock"></i> Joined: <br> <?php echo date("g:i a, F j, Y", strtotime($created_at)); ?></p>
                                <p class="card-text fw-bold"><i class="fas fa-user-circle"></i> <?php echo ucfirst($user_type); ?></p>
                                <div class="text-center">
                                    <?php if ($user_type !== 'admin') : ?>
                                        <a href="view_user.php?id=<?php echo $id; ?>" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
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
        } else {
            // Query execution failed
            echo '<div class="col"><div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div></div>';
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Prepare statement failed
        echo '<div class="col"><div class="alert alert-danger" role="alert">Error: Could not prepare SQL statement.</div></div>';
    }
}
// Close connection
mysqli_close($conn);
?>
