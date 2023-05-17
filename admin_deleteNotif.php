<?php
include('db.php');
$report_id = $_GET['report_id'];
$sql = "DELETE FROM ReportPost WHERE reportid = $report_id";
mysqli_query($conn, $sql);
//close connection and redirect to admin_notif.php
mysqli_close($conn);
header('Location: admin_notif.php');
?>