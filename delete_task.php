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

// check if the ID parameter is set
if (isset($_POST['id'])) {
    // escape the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // delete the row with the matching ID from the adminTask table
    $sql = "DELETE FROM adminTask WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin_index.php");
    } else {
        echo "Error deleting task: " . mysqli_error($conn);
    }
} else {
    echo "No ID parameter specified";
}

// close database connection
mysqli_close($conn);
?>
