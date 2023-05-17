<?php
    session_start();
    include("db.php");

    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $profilePicture = $_SESSION['profilePicture'];
        $pictureType = $_SESSION['pictureType'];
    }

    // Define $setClause
    $setClauses = array();
    $setClause = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //check if username already exists
        $sql = "SELECT * FROM User WHERE username = '$username' OR email = '$email';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<script>alert("Username or email already exists");</script>';
        }else{
            // Build $setClause based on submitted fields
            if (!empty($_POST['email'])) {
                $setClauses[] = "email=?";
                $email = $_POST['email'];
            }
            if (!empty($_POST['username'])) {
                $setClauses[] = "username=?";
                $newUsername = $_POST['username'];
            }
            if (!empty($_POST['password1'])) {
                $setClauses[] = "password=?";
                $newPassword = md5($_POST["password1"]);
            }
            // Check if a new profile picture was uploaded
            if (isset($_FILES['picture']['tmp_name']) && $_FILES['picture']['tmp_name'] != "") {
                $setClauses[] = "profilePicture=?, pictureType=?";

                // Get picture data
                $newProfilePicture = file_get_contents($_FILES['picture']['tmp_name']);
                $pictureType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));

                $pictureSize = $_FILES['picture']['size'];
                if ($pictureSize > 100000) {
                    echo '<script>alert("The file size must be less than 100KB"); window.location.href="user_createAccount.php";</script>';
                    exit();
                }
            }

            // Concatenate $setClauses
            if (!empty($setClauses)) {
                $setClause = implode(",", $setClauses);
            }

            // Update User table
            if (!empty($setClause)) {
                $sql = "UPDATE User SET " . $setClause . " WHERE username=?";
                $stmt = $conn->prepare($sql);
                $paramArr = array();
                if (!empty($email)) {
                    $paramArr[] = &$email;
                }
                if (!empty($newUsername)) {
                    $paramArr[] = &$newUsername;
                }
                if (!empty($newPassword)) {
                    $paramArr[] = &$newPassword;
                }
                if (isset($_FILES['picture']['tmp_name']) && $_FILES['picture']['tmp_name'] != "") {
                    $null = null;
                    $paramArr[] = &$null;
                    $paramArr[] = &$pictureType;
                    $stmt->send_long_data(count($paramArr)-1, $newProfilePicture);
                }
                $paramArr[] = &$username;
                $stmt->bind_param(str_repeat("s", count($paramArr)), ...$paramArr);
                if ($stmt->execute() === TRUE) {
                    header("Location: login.php"); // redirect to login page
                    exit();
                } else {
                    echo "Error updating profile: " . $conn->error;
                }
                $stmt->close();
            }
            $conn->close();
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/editProfile.css">
    <script type="text/javascript" src='js/editProfile.js'></script>
    <title>Edit Profile</title>
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
                            <p>Create Post</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Left column with mock profile picture -->
                <div class="card">
                    <?php echo '<img src="data:image/'.$pictureType.';base64,'.base64_encode($profilePicture).'" height="400"/>'; ?> 
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $username?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Right column with form for editing user information -->
                <div class="row justify-content-center">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                                <h5>Just fill the fields you want to change</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" onsubmit="return validateEmail() && validatePassword();">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password1" name="password1" placeholder="Enter password">
                                    </div>
                                    <div class="form-group">
                                        <label for="re-password">Re-enter Password</label>
                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Re-enter password">
                                        <small class="text-danger" id="passwordError"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="picture">Profile Pircture (Max 100KB)</label>
                                        <input type="file" class="form-control" id="picture" name="picture" accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                    <button type="submit" class="btn btn-primary"
                                        style="margin-top: 5px; float: right;">Edit Profile</button>
                                </form>
                                <div>
                                    
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</body>

</html>