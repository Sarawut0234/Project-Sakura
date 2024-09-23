<?php
include '../includes/db.php';
session_start();

header('Content-Type: text/html; charset=utf-8'); // กำหนด header ให้เป็น HTML

try {
    // ลบข้อความที่มีอายุมากกว่า 5 นาที (300 วินาที)
    $stmt = $conn->prepare("DELETE FROM chats WHERE timestamp < NOW() - INTERVAL 5 MINUTE");
    $stmt->execute();

    // ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
    if (!isset($_SESSION['user_id'])) {
        echo '<p>Please log in to view the chat.</p>';
        exit();
    }

    // ดึงข้อมูลแชทจากฐานข้อมูล โดยเรียงจากข้อความเก่าสุดไปหาข้อความใหม่สุด
    $stmt = $conn->prepare("SELECT users.username, chats.message, chats.timestamp, chats.color 
                            FROM chats 
                            JOIN users ON chats.user_id = users.id 
                            ORDER BY chats.timestamp ASC");
    $stmt->execute();
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // แสดงผลข้อความแชท
    foreach ($chats as $chat) {
        $currentTime = time();
        $messageTime = strtotime($chat['timestamp']);
        $timeElapsed = $currentTime - $messageTime;
        $countdown = max(0, 300 - $timeElapsed); // 300 วินาทีคือ 5 นาที

        // ใช้สีที่เลือกโดยผู้ใช้
        $color = htmlspecialchars($chat['color']);
        
        // ตรวจสอบว่าเป็นข้อความของ admin หรือไม่
        $messageClass = $chat['username'] === 'admin' ? 'admin-message' : '';

        // แสดงผลข้อความ
        echo '<div class="chat-message ' . $messageClass . '" style="color: ' . $color . ';">';
        echo '<strong>' . htmlspecialchars($chat['username']) . ':</strong> ';
        echo htmlspecialchars($chat['message']);
        echo ' <span class="countdown">' . round($countdown) . '</span> seconds remaining';
        echo ' <span class="timestamp">' . date('H:i:s', $messageTime) . '</span>'; // แสดงเวลาส่งข้อความ
        echo '</div>';
    }
} catch (PDOException $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
