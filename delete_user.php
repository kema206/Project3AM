<?php
include("db.php");
$username = $_GET['username'];
// Delete user
$sql = "DELETE FROM User WHERE username = '$username'";
// Delete user's post
$sql2 = "DELETE FROM Post WHERE username = '$username'";
// Execute query
mysqli_query($conn, $sql);
mysqli_query($conn, $sql2);
// Redirect to admin_userList.php
header("Location: admin_userList.php");

?>