<?php
session_start();
include("db.php");

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $profilePicture = $_SESSION['profilePicture'];
    $pictureType = $_SESSION['pictureType'];
    $logout_url = 'logout.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // retrieve the form data
        $title = $_POST["post_title"];
        $content = $_POST["post_text"];

        // prepare the SQL statement to insert the data
        $stmt = $conn->prepare("INSERT INTO Post (title, content, username, category, likes) VALUES (?, ?, ?, ?, 0)");
        $category = $_POST['category'];
        $stmt->bind_param("ssss", $title, $content, $username, $category);

        // execute the SQL statement
        if ($stmt->execute()) {
            header("Location: index.php"); // redirect to home page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // close the prepared statement and database connection
        $stmt->close();
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
    <script type="text/javascript" src='js/createPost.js'></script>
    <link rel="stylesheet" href="css/createPost.css">
    <title>Create Post</title>
</head>
<body style="background-color: #EBECF0;">
    <!-- navbar -->
    <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/3am.png" alt="3AM Logo" height="35">
            </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link active" href="user_createPost.php">
                            <span class="fa fa-home fa-plus"></span>
                            <p>Create Post</p>
                        </a>
                    </li>
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
    <!-- posts layout -->
    <!-- form -->
    <?php if (isset($_SESSION['username'])) { ?>
    <div class="container mt-5">
        <div class="row content">
            <div class="col-md-8">
                Create a post
                    <hr>
                <div class="card post">
                    <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" onsubmit="return validateTitle() && validateText();">
                            <div class="form-group">
                            <input type="text" class="form-control" id="post_title" name="post_title" placeholder="Enter title" required>
                                <small class="text-danger" id="titleError"></small>
                            </div>
                            <hr>
                            <div class="form-group">
                                <textarea id="post_text" name="post_text" rows="5" cols="33" placeholder="Enter text" required></textarea>
                                <small class="text-danger" id="textError"></small>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="sports">Sports</option>
                                    <option value="coding">Coding</option>
                                    <option value="politics">Politics</option>
                                </select>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary"
                                style="margin-top: 5px; float: right;">Post</button>
                        </form>
                            <br>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card post rules">
                    <h4>Rules for posting in 3AM</h4>
                    <ol>
                        <li>Behave like a human</li>
                        <li>Post appropriate content</li>
                        <li>Search for duplicates before posting</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <?php } else {
        echo "<div class='centered' style='color: grey; text-align: center; display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 20px;'>";
        echo "<p>Please <a href='login.php'>login</a> to create a post.</p>";
        echo "</div>";
    }
?>
</body>
</html>