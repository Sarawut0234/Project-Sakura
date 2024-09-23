<?php
include '../includes/db.php';

// ลบข้อความที่มีอายุมากกว่า 5 นาที (300 วินาที)
$stmt = $conn->prepare("DELETE FROM chats WHERE timestamp < NOW() - INTERVAL 5 MINUTE");
$stmt->execute();

echo 'Old messages deleted successfully';
?>
