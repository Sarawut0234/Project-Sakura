// chat_server.php
<?php
// ใช้ WebSocket หรือระบบ AJAX ที่เหมาะสม
// ตัวอย่างนี้ใช้การดึงข้อมูลผ่าน AJAX

// เชื่อมต่อกับฐานข้อมูลและดึงข้อความใหม่
include '../includes/db.php';

$query = "SELECT * FROM messages ORDER BY timestamp DESC";
$result = mysqli_query($conn, $query);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
