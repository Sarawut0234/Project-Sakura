<?php
include '../includes/db.php';

header('Content-Type: application/json');

session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// ลบข้อความที่มีอายุมากกว่า 5 นาที
$stmt = $conn->prepare("DELETE FROM chats WHERE timestamp < NOW() - INTERVAL 5 MINUTE");
$stmt->execute();

// ดึงข้อมูลแชทจากฐานข้อมูล
$stmt = $conn->prepare("SELECT users.username, chats.message, chats.timestamp, chats.color 
                        FROM chats 
                        JOIN users ON chats.user_id = users.id 
                        ORDER BY chats.timestamp ASC");
$stmt->execute();
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// จัดรูปแบบข้อความ
$formattedChats = array_map(function($chat) {
    return [
        'username' => htmlspecialchars($chat['username'], ENT_QUOTES, 'UTF-8'),
        'text' => htmlspecialchars($chat['message'], ENT_QUOTES, 'UTF-8'),
        'color' => htmlspecialchars($chat['color'], ENT_QUOTES, 'UTF-8'),
        'timestamp' => $chat['timestamp']
    ];
}, $chats);

// ส่ง JSON response
echo json_encode(['messages' => $formattedChats]);
?>
