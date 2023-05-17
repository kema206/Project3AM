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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src='js/bootstrap.bundle.min.js'></script>
    <script type="text/javascript" src='js/adminIndex.js'></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/adminIndex.css">
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
                        <a class="nav-link active" href="admin_index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_userList.php">UserLists</a>
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
    <div class="container-fluid" style="margin-top: 10px;">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h4>User Statistics</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" id="total-users">Total Users: </h5>
                        <p class="card-text" id="total-posts">Total Post: </p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">To-Do List</h5>
                        <form id="task-form">
                            <div class="form-group">
                                <label for="new-task">New Task</label>
                                <input type="text" class="form-control" id="new-task" placeholder="Enter task">
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px; float: right;">Add Task</button>
                            <div style="clear:both;"></div>
                        </form>
                        <hr>
                        <table id="task-list" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>