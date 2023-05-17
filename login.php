<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password_un = $_POST['password'];
    $password = md5($password_un);

    // Query the database to check if the user exists
    $query = "SELECT * FROM User WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // User exists, set session variables and redirect to index.php
        $row = mysqli_fetch_array($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['profilePicture'] = $row['profilePicture'];
        $_SESSION['pictureType'] = $row['pictureType'];
        header('Location: index.php');
    } else {
        //check if it's admin login
        $query = "SELECT * FROM Admin WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            // User exists, set session variables and redirect to index.php
            $row = mysqli_fetch_array($result);
            $_SESSION['username'] = $row['username'];
            header('Location: admin_index.php');
        }
        else{
            // Invalid credentials, display error message
            $error = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src='js/bootstrap.bundle.min.js'></script>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login Page</title>
</head>
<!-- navbar -->
<nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="img/3am.png" alt="3AM Logo" height="35">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <span class="fa fa-home"></span>
                            <p>Home</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_populars.php">
                            <span class="fa fa-line-chart"></span>
                            <p>Popular Post</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_notification.php">
                            <span class="fa fa-bell"></span>
                            <p>Notification</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_createPost.php">
                            <span class="fa fa-plus"></span>
                            <p>Create Post</p>
                        </a>
                    </li>
                </ul>
        </div>
    </div>
</nav>

<body style="background-color: #EBECF0;">
    <!-- form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        // Display error message if set
                        if (isset($error)) {
                            echo "<p class='text-danger'>$error</p>";
                        }
                        ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter username">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px; float: right;">Log
                                in</button>
                        </form>
                        <div>
                            <br>
                            <p>Don't have account? <a href="user_createAccount.php"> create an account! </a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>