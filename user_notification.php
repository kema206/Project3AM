<?php
session_start();
include("db.php");
// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $profilePicture = $_SESSION['profilePicture'];
    $pictureType = $_SESSION['pictureType'];
    $logout_url = 'logout.php';
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
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/user_notification.css">
    <title>User Notification</title>
</head>
<body style="background-color: #EBECF0;">
<nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/3am.png" alt="3AM Logo" height="35">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
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
                        <a class="nav-link active" href="user_notification.php">
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
                <!-- only display dropdown navbar if user is logged-in -->
                <?php
                if (isset($_SESSION['username'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php 
                                 echo "$username &nbsp";
                                 echo '<img src="data:image/'.$pictureType.';base64,'.base64_encode($profilePicture).'" height="35">'; 
                                 ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="user_editProfile.php">Edit Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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


<?php
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        // Retrieve notifications for the user's posts from the database
        $opened = 0;
        $notifications_query = "SELECT Comment.username, Post.title, Post.id, Comment.opened, Comment.commentId FROM Comment JOIN Post ON Comment.postId = Post.id WHERE Post.username = '$username' AND opened = $opened";
        $notifications_result = mysqli_query($conn, $notifications_query);

        // Display notifications if there are any
        if (mysqli_num_rows($notifications_result) > 0) {
            echo "<div class='container'>";
            echo "Notifications";
            echo "<hr>";
            while ($notification = mysqli_fetch_assoc($notifications_result)) {
                $commenter = $notification['username'];
                $postTitle = $notification['title'];
                $post_id = $notification['id'];
                $comment_id = $notification['commentId'];
                echo "<div class='row notif'>";
                echo "<a href='update_notification.php?comment_id=$comment_id&post_id=$post_id'>";
                echo "<h4>From: $commenter</h4>";
                echo "<p>1 new comment on your post '$postTitle'</p>";
                echo "</a>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='centered' style='color: grey; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 20px;'>";
            echo "<p><span class='fa fa-comment'></span> No new comments</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='centered' style='color: grey; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 20px;'>";
        echo "<p>Please <a href='login.php'>login</a> to see notifications.</p>";
        echo "</div>";
    }
?>

</body>
</html>