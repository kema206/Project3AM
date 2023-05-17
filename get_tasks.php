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

// Get tasks and ids
$sql = "SELECT * FROM adminTask WHERE adminusername = '$username' ";
$result = mysqli_query($conn, $sql);

$id = array();
$task = array();
while ($row = mysqli_fetch_assoc($result)) {
    $id[] = $row['id'];
    $task[] = $row['task'];
}

// Create an associative array containing both arrays
$data = array(
    'id' => $id,
    'task' => $task
);

echo json_encode($data);
?>