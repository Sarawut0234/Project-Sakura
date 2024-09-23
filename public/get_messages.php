<?php
include '../includes/db.php';
session_start();

header('Content-Type: application/json');

$stmt = $conn->prepare("SELECT * FROM chats ORDER BY timestamp DESC");
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['messages' => $messages]);
?>
