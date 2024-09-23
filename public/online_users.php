<?php
include '../includes/db.php';

$time_limit = date("Y-m-d H:i:s", strtotime('-5 minutes'));
$stmt = $conn->prepare("SELECT username FROM users WHERE last_activity > :time_limit AND role = 'admin'");
$stmt->bindParam(':time_limit', $time_limit, PDO::PARAM_STR);
$stmt->execute();
$online_users = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
