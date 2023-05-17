<?php
session_start();
include("db.php");

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $profilePicture = $_SESSION['profilePicture'];
    $pictureType = $_SESSION['pictureType'];
    $logout_url = 'logout.php';

    // Need this for inserting comment to db.
    // Get the post id
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post_id = $_POST['post_id'];
    } else {
        //get post id from admin_notif.php
        $post_id = $_GET['id'];
    }

    // Get the post comment
    if (isset($_POST['comment'])) {
        $content = $_POST['comment'];
        if ($content != '') {
            // insert comment to db
            $sql = "INSERT INTO Comment (username, content, postId, opened) VALUES ('$username', '$content', '$post_id' , 0)";
            // execute query
            mysqli_query($conn, $sql);
        }
    }
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
                            <span class="fa fa-home fa-plus"></span>
                            <p>Create A Post</p>
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
    <!-- posts layout -->
    <div class="container">
        <div class="row justify-content-center">
            <!-- post section -->
            <?php
            $sqlPost = "SELECT title, content FROM Post WHERE id = $post_id";
            $resultPost = mysqli_query($conn, $sqlPost);
            $rowPost = mysqli_fetch_assoc($resultPost);
            $postTitle = $rowPost['title'];
            $postContent = $rowPost['content'];
            ?>
            <div class="col-md-8">
                <!-- dynamic title and content -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">
                            <?php echo $postTitle; ?>
                        </h2>
                        <p class="card-text">
                            <?php echo $postContent; ?>
                        </p>
                    </div>
                </div>

                <!-- dynamic comments -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Comments</h5>
                        <?php
                        $sqlComment = "SELECT Comment.content, Comment.username, User.profilePicture, User.pictureType FROM Comment JOIN User ON Comment.username = User.username WHERE postId = $post_id";
                        $resultComment = mysqli_query($conn, $sqlComment);
                        // comment box
                        echo '<form method="POST" action="post.php">';
                        echo '<div class="form-group">';
                        echo '<textarea class="form-control" rows="5" id="comment" name="comment" placeholder="what are you thoughts?"></textarea>';
                        echo '</div>';
                        echo '<div>';
                        echo '<button type="submit" class="btn btn-primary" style="margin-bottom: 2em">Post</button>';
                        echo '</div>';
                        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                        echo '</form>';
                        while ($rowComment = mysqli_fetch_assoc($resultComment)) {
                        $commentContent = $rowComment['content'];
                        $commentUsername = $rowComment['username'];
                        ?>
                        <div class="media mb-4">
                            <?php echo '<img src="data:image/'.$pictureType.';base64,'.base64_encode($profilePicture).'" height="50" width="50" class="d-flex mr-3 rounded-circle">'; ?>
                            <div class="media-body">
                            <h5 class="mt-0"><?php echo htmlspecialchars($commentUsername); ?></h5>
                            <?php echo htmlspecialchars($commentContent); ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Leave a Comment</h5>
                        <form>
                            <div class="form-group">
                                <label for="comment-body">Comment</label>
                                <textarea class="form-control" id="comment-body" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div> -->
            </div>
        </div>
    </div>


</body>

</html>