<?php
session_start();
include("db.php");
// Check if the admin is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $logout_url = 'logout.php';

     // Retrieve reported posts from database
     $sql = "SELECT * FROM ReportPost";
     $result = mysqli_query($conn, $sql);
} else {
    $username = 'Log In!';
    $logout_url = 'login.php';
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
                        <a class="nav-link" href="admin_userList.php">UserLists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_notif.php">Notification</a>
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Reported Posts</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Reporter</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Loop through each reported post and display it in the table
                            while ($row = mysqli_fetch_assoc($result)) {
                                $report_id = $row['reportid'];
                                $post_id = $row['postid'];
                                $reporter = $row['reporter'];
                                $reason = $row['reason'];
                            ?>
                            <tr>
                                <td><a href="post.php?id=<?php echo $post_id ?>"><?php echo $post_id ?></a></td>
                                <td><?php echo $reporter ?></td>
                                <td><?php echo $reason ?></td>
                                <td><a href="admin_deleteNotif.php?report_id=<?php echo $report_id ?>" class='btn btn-warning'>Reviewed</a></td>
                            </tr>
                            <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>