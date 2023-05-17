<?php
include("db.php");

// get total number of users
$sql = "SELECT COUNT(*) as total_users FROM User";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users'];

// get total number of posts
$sql = "SELECT COUNT(*) as total_posts FROM Post";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_posts = $row['total_posts'];

// create JSON object
$data = array(
    'total_users' => $total_users,
    'total_posts' => $total_posts
);

echo json_encode($data);
?>
