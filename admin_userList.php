<?php
session_start();
include("db.php");
// Check if the admin is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $logout_url = 'logout.php';
} else {
    $username = 'Log In!';
    $logout_url = 'login.php';
}
// Fetch user data
$user_query = "SELECT username, email FROM User";
$user_result = mysqli_query($conn, $user_query);

// Count user posts
function count_posts($conn, $username) {
    $post_query = "SELECT COUNT(*) as post_count FROM Post WHERE username = ?";
    $stmt = mysqli_prepare($conn, $post_query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['post_count'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src='js/bootstrap.bundle.min.js'></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Admin Portal</title>
</head>

<body>
    <!-- admin navbar -->
    <nav class="navbar navbar-dark bg-primary navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">3AM ADMIN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_userList.php">UserLists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_notif.php">Notification</a>
                    </li>
                </ul>
                <!-- only display dropdown navbar if admin is logged-in -->
                <?php
                if (isset($_SESSION['username'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $username ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav">
                        <li class="nav-item me-auto mb-2 mb-lg-0">
                            <a class="nav-link" href="login.php">Log In!</a>
                        </li>
                    </ul>
                <?php endif;
                ?>
            </div>
        </div>
    </nav>
    <!-- END OF NAVBAR -->
    <div class="container" style="margin-top: 10px;">
        <h2>List of Users</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Total Posts</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (mysqli_num_rows($user_result) > 0) {
                        while ($user_row = mysqli_fetch_assoc($user_result)) {
                            $user_name = $user_row['username'];
                            $user_email = $user_row['email'];
                            $post_count = count_posts($conn, $user_name);
                            echo "<tr>
                                    <td><a href='#'>$user_name</a></td>
                                    <td>$user_email</td>
                                    <td>$post_count</td>
                                    <td><a href='delete_user.php?username=$user_name' class='btn btn-danger'>Delete</a></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No users found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>