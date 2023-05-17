<?php
session_start();
include("db.php");
// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $profilePicture = $_SESSION['profilePicture'];
    $pictureType = $_SESSION['pictureType'];
    $logout_url = 'logout.php';
} 

$post_id = $_GET['post_id'];
$comment_id= $_GET['comment_id'];
$update_query = "UPDATE Comment SET opened = 1 WHERE commentId = $comment_id";
mysqli_query($conn, $update_query);
header("Location: post.php?id=" . $post_id);

?>