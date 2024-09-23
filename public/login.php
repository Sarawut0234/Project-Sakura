<?php
include '../includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบชื่อผู้ใช้และรหัสผ่าน
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // ตั้งค่าตัวแปรเซสชัน
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // อัปเดต last_activity
        $stmt = $conn->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);

        // เปลี่ยนเส้นทางไปยังหน้า index.php
        header("Location: index.php");
        exit();
    } else {
        // ถ้าชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง ให้ตั้งค่าข้อความแจ้งเตือน
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <style>
        body {
            background-image: url('../assets/images/Sakura.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
    <script type="text/javascript">
        // Disable F12 and other developer tools shortcuts
        document.addEventListener('keydown', function(event) {
            if (event.keyCode == 123) { // F12
                event.preventDefault();
            }
            if (event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) { // Ctrl+Shift+I
                event.preventDefault();
            }
            if (event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) { // Ctrl+Shift+J
                event.preventDefault();
            }
            if (event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) { // Ctrl+U
                event.preventDefault();
            }
            if (event.ctrlKey && event.keyCode == 'C'.charCodeAt(0)) { // Ctrl+C
                event.preventDefault();
            }
        });

        // Disable right-click
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        // Disable text selection
        document.addEventListener('selectstart', function(event) {
            event.preventDefault();
        });

        // Disable copy event
        document.addEventListener('copy', function(event) {
            event.preventDefault();
        });

        // Disable drag event
        document.addEventListener('dragstart', function(event) {
            event.preventDefault();
        });
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <a href="register.php" class="button">ลงทะเบียน</a>
    </div>
</body>
</html>
