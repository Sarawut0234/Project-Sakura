<?php
include '../includes/db.php';
session_start();

echo "<script>
    if (Notification.permission === 'granted') {
        new Notification('You have been logged out.');
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                new Notification('You have been logged out.');
            }
        });
    }
</script>";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // อัปเดต last_activity ให้เป็นเวลาที่ผ่านมาแล้วเพื่อให้ผู้ใช้ออฟไลน์ทันที
    $stmt = $conn->prepare("UPDATE users SET last_activity = DATE_SUB(NOW(), INTERVAL 10 MINUTE) WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

// ล้าง session และเปลี่ยนเส้นทางไปยังหน้า login
session_destroy();
header("Location: login.php");
exit();
?>
