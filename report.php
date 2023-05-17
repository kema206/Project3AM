<?php
include('db.php');
session_start();
$reporter = $_SESSION['username'];
$postId = $_GET['post_id'];
$reason = $_GET['reason'];
$query = "INSERT INTO ReportPost (postid, reporter, reason) VALUES ('$postId', '$reporter', '$reason')";
//execute query
mysqli_query($conn, $query);
//close connection
mysqli_close($conn);
header("Location: index.php");  
?>