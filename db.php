<?php
$host = 'cosc360.ok.ubc.ca'; 
$user = '32741829'; // MySQL user name
$pass = '32741829'; // MySQL password
$db = 'db_32741829'; // MySQL database name

// $host = 'localhost'; 
// $user = 'root'; // MySQL user name
// $pass = ''; // MySQL password
// $db = 'db_32741829'; // MySQL database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
