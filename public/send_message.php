<?php
include '../includes/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['message']) && isset($_POST['color'])) {
    $message = trim($_POST['message']);
    $color = trim($_POST['color']);

    if ($message !== '') {
        try {
            // ลบข้อความที่เก่ากว่า 5 นาที
            $stmt = $conn->prepare("DELETE FROM chats WHERE timestamp < NOW() - INTERVAL 5 MINUTE");
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO chats (user_id, message, color, timestamp) VALUES (:user_id, :message, :color, NOW())");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $stmt->bindParam(':color', $color, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
            } else {
                echo json_encode(['error' => 'Failed to send message']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')]);
        }
    } else {
        echo json_encode(['error' => 'Message is empty']);
    }
} else {
    echo json_encode(['error' => 'Message or color not set']);
}
?>
