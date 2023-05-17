<?php
include("db.php");

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $query = "UPDATE Post SET likes = likes + 1 WHERE id = $post_id";
    mysqli_query($conn, $query);
}
?>