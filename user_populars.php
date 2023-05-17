<?php
    session_start();
    include("db.php");
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $profilePicture = $_SESSION['profilePicture'];
        $pictureType = $_SESSION['pictureType'];
        $logout_url = 'logout.php';
    } else {
        $username = 'Log In!';
        $logout_url = 'login.php';
    }

    // Retrieve posts from the database
    
    if(isset($_GET['title'])){
        $title = $_GET['title'];
        $posts_query = "SELECT id, title, content, username, likes FROM Post WHERE title LIKE '%$title%' ORDER BY likes DESC";
    }else{
        $posts_query = "SELECT id, title, content, username, likes FROM Post ORDER BY likes DESC";
    }
    $posts_result = mysqli_query($conn, $posts_query);
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
    <link rel="stylesheet" href="css/user_populars.css">
    <title>Popular Posts</title>
</head>
<!-- navbar -->

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
                        <a class="nav-link active" href="#">
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
                    <form class="d-flex" action="user_populars.php" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Search by title" aria-label="Search" name="title">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </ul>
                <!-- only display dropdown navbar if user is logged-in -->
                <?php 
                if (isset($_SESSION['username'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php 
                                    echo "$username &nbsp";
                                    echo '<img src="data:image/'.$pictureType.';base64,'.base64_encode($profilePicture).'" height="35">'; 
                                ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="user_editProfile.php">Edit Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
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
    <div class="post_section">
    <!-- post title -->
        Popular posts
        <hr>
        <!-- post content -->
        <?php
        // Loop through the posts and display them
        while ($post = mysqli_fetch_assoc($posts_result)) {
            // Get post data
            $id = $post['id'];
            $title = $post['title'];
            $content = $post['content'];
            $username = $post['username'];
            $likes = $post['likes'];

            echo "<div class='row post'>";
            echo "<div class='left'>";
            if (isset($_SESSION['username'])) {
                echo "<button class='up-button' onclick='updateLikes($id)'><span class='fa fa-arrow-up'></span></button>";
            } else {
                echo "<a href='login.php'>Login</a>";
            }
            echo "<p class='text' id='likes-$id'>$likes</p>"; // added id attribute to likes count element
            echo "</div>";
            echo "<div class='right'>";
            echo "<div class='post_title'>";
            echo "<h4>$title</h4><h5> Posted by $username</h5>";
            echo "</div>";
            echo "<p>$content</p>";
            if (isset($_SESSION['username'])) {
                echo "<a class='comment-button' href='post.php?id=$id' style='color: black; text-decoration: none;'><span class='fa fa-comment-o'></span> Comment</a>";
            } else{
                echo '<span> <a href="login.php">Login</a> to comment on this post</span>';
            }
            echo "</div>";
            echo "</div>";
        }
    ?>

</div>  

<script>
    function updateLikes(post_id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Update the likes count in the UI
                var likesElem = document.getElementById("likes-" + post_id);
                likesElem.textContent = this.responseText;

                location.reload();
            }
        };
        xmlhttp.open("GET", "update_likes.php?post_id=" + post_id, true);
        xmlhttp.send();
    }
</script>
</body>

</html>