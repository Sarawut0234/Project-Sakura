<?php
include '../includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ตรวจสอบว่าชื่อผู้ใช้หรืออีเมลมีอยู่แล้วหรือไม่
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch();

    if ($user) {
        // ถ้าชื่อผู้ใช้หรืออีเมลมีอยู่แล้ว ให้ตั้งค่าข้อความแจ้งเตือน
        $_SESSION['error'] = "Username or Email already exists. Please choose another one.";
        header("Location: register.php");
        exit();
    } else {
        // เพิ่มผู้ใช้ในฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$username, $email, $password]);

        // เปลี่ยนเส้นทางไปยังหน้า login.php
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
    <div class="register-container">
        <h1>Register</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">ลงทะเบียน</button>
        </form>
        <a href="login.php" class="button">เข้าสู่ระบบ</a>
    </div>
</body>
</html>

