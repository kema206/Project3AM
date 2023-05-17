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
    <link rel="stylesheet" href="css/postSection.css">
    <title>3AM</title>
</head>

<body style="background-color: #EBECF0;">
    <!-- navbar -->
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
                        <a class="nav-link active" href="index.php">
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
                            <span class="fa fa-home fa-plus"></span>
                            <p>Create A Post</p>
                        </a>
                    </li>
                      <!-- Search bar -->
                    <form class="d-flex" action="index.php" method="GET">
                        <input class="form-control me-2" type="search" placeholder="Search by title" aria-label="Search" name="title">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
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
    <!-- posts layout -->
    <div class="container">
        <div class="row" style="margin-top: 5%;">
            <!-- post section -->
            <div class="col-md-7">
                <?php
                // fetch all posts from the database
                // check if category or title parameter is set in URL
                if (isset($_GET['category'])) {
                    $category = $_GET['category'];
                    $query = "SELECT * FROM Post WHERE category = '$category'";
                } else if(isset($_GET['title'])){
                    $title = $_GET['title'];
                    $query = "SELECT * FROM Post WHERE title LIKE '%$title%'";
                }else {
                    $query = "SELECT * FROM Post";
                }

                $result = mysqli_query($conn, $query);

                // loop through the posts and generate HTML for each post
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $content = $row['content'];
                    $poster = $row['username'];
                
                    // generate HTML for the post
                    echo '<div class="post row">';
                    echo '<div class="col-12">';
                    echo '<h2 style="font-size: 1.5rem;">' . htmlspecialchars($title) . '<span style="font-size: 1rem; color:grey"> by&nbsp;' . $poster . '</span></h2>';
                    
                    if(isset($_SESSION['username'])) {
                        // add warning logo for reporting post
                        echo '<a href="#" onclick="reportPost(' . $id . ')"><span class="fa fa-exclamation-triangle" style="float: right; margin-top: -2em; margin-right: 1em; color: #FF0000;"></span></a>';
                    }
                        // add javascript function to prompt user for reason for reporting post
                    echo '<script>
                        function reportPost(post_id) {
                        var reason = prompt("Please enter a reason for reporting this post:");

                        // highlight the prompt box if the user does not enter a reason
                        if (reason === null || reason === "") {
                            alert("Please enter a reason for reporting this post.");
                            return;
                        }

                        // redirect to report.php with the post_id and reason
                        window.location.href = "report.php?post_id=" + post_id + "&reason=" + reason;
                        }
                        </script>';
                    echo '</div>';
                    echo '<div class="col-12">';
                    echo '<div class="row content">' . htmlspecialchars($content) . '</div>';
                    echo '<div class="row commentSection">';
                    echo '<div class="col">';
                    // if the user is not logged in, display a message
                    if(!isset($_SESSION['username'])) {
                        echo '<span> <a href="login.php">Login</a> to comment on this post</span>';
                    }else{
                        echo 'Comment as <span>' . htmlspecialchars($username) . '</span>';
                        echo '<form method="POST" action="post.php">';
                        echo '<div class="form-group">';
                        echo '</div>';
                        echo '<div>';
                        echo '<button type="submit" class="btn btn-primary float-start" style="margin-bottom: 2em">See Comments</button>';
                        echo '</div>';
                        echo '<input type="hidden" name="post_id" value="' . $id . '">';
                        echo '</form>';
                    }
                    echo '</div>';
                    echo '<div class="col">';
                    //only display the form if the user is logged in
                    if(isset($_SESSION['username'])) {
                        echo '<form method="POST" action="post.php">';
                        echo '<div class="form-group">';
                        echo '<textarea class="form-control" rows="5" id="comment" name="comment" placeholder="what are you thoughts?"></textarea>';
                        echo '</div>';
                        echo '<div>';
                        echo '<button type="submit" class="btn btn-primary" style="margin-bottom: 2em">Post</button>';
                        echo '</div>';
                        echo '<input type="hidden" name="post_id" value="' . $id . '">';
                        echo '</form>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }

                // free the result set
                mysqli_free_result($result);

                // close the database connection
                mysqli_close($conn);
                ?>
            </div>
            <div class="col-md-1"> </div>
            <!-- right sidebar -->
            <div class="col-md-4">
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Topics</h5>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td><a href="?category=sports">Sports</a></td>
                                    </tr>
                                    <tr>
                                        <td><a href="?category=coding">Coding</a></td>
                                    </tr>
                                    <tr>
                                        <td><a href="?category=politics">Politics</a></td>
                                    </tr>
                                    <tr>
                                        <td><a href="index.php">Any</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>