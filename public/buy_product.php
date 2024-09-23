<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // เพิ่มคำสั่งซื้อในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);

    // เปลี่ยนเส้นทางไปยังหน้าแสดงคำสั่งซื้อ
    header("Location: orders.php");
    exit();
}

?>
