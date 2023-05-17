<?php
include("db.php");
session_start();

// Check if the admin is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $logout_url = 'logout.php';
} else {
    $username = 'Log In!';
    $logout_url = 'login.php';
}

if (isset($_POST['task']) && !empty($_POST['task'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $sql = "INSERT INTO adminTask (adminusername, task) VALUES ('$username','$task')";
    mysqli_query($conn, $sql);
}
?>
