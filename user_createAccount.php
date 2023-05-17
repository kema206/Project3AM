<?php

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // retrieve the form data
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = md5($_POST["password1"]);
    $profilePicture = file_get_contents($_FILES["picture"]["tmp_name"]);
    $imageFileType = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
    
    //check if username already exists
    $sql = "SELECT * FROM User WHERE username = '$username' OR email = '$email';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<script>alert("Username or email already exists");</script>';
    }else{
        // prepare the SQL statement to insert the data

        $pictureSize = $_FILES['picture']['size'];
        if ($pictureSize > 100000) {
            echo '<script>alert("The file size must be less than 100KB"); window.location.href="user_createAccount.php";</script>';
            exit();
        }        

        $stmt = $conn->prepare("INSERT INTO User (email, username, password, profilePicture, pictureType) VALUES (?, ?, ?, ?, ?)");
        $null = null;
        $stmt->bind_param("sssbs", $email, $username, $password, $null, $imageFileType);
        $stmt->send_long_data(3, $profilePicture);
        
        // execute the SQL statement
        if ($stmt->execute()) {
        header("Location: login.php"); // redirect to login page
        exit();
        } else {
        echo "Error: " . $stmt->error;
        }
        
        // close the prepared statement and database connection
        $stmt->close();
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
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/createAccount.css">
    <script type="text/javascript" src='js/createAccount.js'></script>
    <title>Create Account Page</title>

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
            </ul>
        </div>
    </div>
</nav>

    <!-- form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Account</h4>
                    </div>
                    <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" onsubmit="return validateEmail() && validatePassword();">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password1" name="password1" placeholder="Enter password" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="re-password">Re-enter Password</label>
                                <input type="password" class="form-control" id="password2" name="password2" placeholder="Re-enter password" required>
                                <small class="text-danger" id="passwordError"></small>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="picture">Profile Pircture (Max 100KB)</label>
                                <input type="file" class="form-control" id="picture" name="picture" placeholder="Enter email" accept="image/png, image/jpeg, image/jpg" required>
                            </div>
                            <small class="text-danger" id="emailError"></small>
                            <br>
                            <button type="submit" class="btn btn-primary"
                                style="margin-top: 5px; float: right;">Create Account</button>
                        </form>
                        <div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>