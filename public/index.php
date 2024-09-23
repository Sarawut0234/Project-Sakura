
<?php
include '../includes/db.php';
include 'online_users.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Update user activity
include 'update_activity.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sakura Project</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Prevent right-click and certain key combinations
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'u' || e.key === 'i' || e.key === 'j' || e.key === 'f12')) {
                e.preventDefault();
            }
        });

        // Disable drag event
        document.addEventListener('dragstart', function(event) {
            event.preventDefault();
        });

        $(document).ready(function() {
            // Function to fetch new messages
            function fetchMessages() {
                $.ajax({
                    url: 'chat_server.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const chatBox = $('#chat-box');
                        chatBox.empty();
                        data.forEach(function(message) {
                            chatBox.append('<div class="chat-message">' + message.content + '</div>');
                        });
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                    }
                });
            }

            // Fetch messages every 5 seconds
            setInterval(fetchMessages, 5000);

            // Music control
            var music = document.getElementById("background-music");
            var musicButton = $('#music-button');
            var isPlaying = localStorage.getItem('musicPlaying') === 'true';

            if (isPlaying) {
                music.play();
                musicButton.html('<i class="fas fa-pause"></i> หยุดเพลง');
            }

            musicButton.click(function(event) {
                event.preventDefault();
                if (isPlaying) {
                    music.pause();
                    musicButton.html('<i class="fas fa-play"></i> เล่นเพลง');
                } else {
                    music.play();
                    musicButton.html('<i class="fas fa-pause"></i> หยุดเพลง');
                }
                isPlaying = !isPlaying;
                localStorage.setItem('musicPlaying', isPlaying);
            });

            // Create sakura petals
            function createPetal() {
                const petal = document.createElement('div');
                petal.classList.add('petal');
                petal.style.left = Math.random() * window.innerWidth + 'px';
                petal.style.animationDuration = Math.random() * 3 + 2 + 's';
                petal.style.opacity = Math.random();
                document.querySelector('.sakura').appendChild(petal);
                petal.addEventListener('animationend', function() {
                    petal.remove();
                });
            }

            setInterval(createPetal, 10000);
        });
    </script>    

    <style>
    /* Background for the entire page */
    body {
        /* background-image: url('../assets/images/background.jpg'); */
        background-image: url('../assets/images/Sakura.jpeg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        -webkit-user-select: none;
    }

    .container {
        padding: 20px;
    }

    /* Chat Button */
    .chat-button {
        background-color: #007bff;
        color: white;
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        font-weight: bold;
        z-index: 1001; /* Ensure it's on top */
    }

    /* Popup Button */
    .popup-button {
        background-color: green;
        color: white;
        position: fixed;
        bottom: 70px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        font-weight: bold;
        z-index: 1001; /* Ensure it's on top of other elements */
    }

    /* Music Button Styling */
    .music-button {
        background-color: #dce535; /* สีพื้นหลังของปุ่มเล่นเพลง */
        color: white; /* สีตัวหนังสือ */
        position: fixed;
        bottom: 120px; /* เลื่อนปุ่มขึ้นจากปุ่มอื่น */
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        font-weight: bold;
        z-index: 1001; /* Ensure it's on top of other elements */
    }

    .music-button:hover {
        background-color: #c5cd21; /* สีปุ่มเมื่อ hover */
    }

    .music-button:active {
        background-color: #dce535; /* สีปุ่มเมื่อคลิก */
    }

    .music-button .fas {
        margin-right: 8px; /* เพิ่มช่องว่างระหว่างไอคอนและข้อความ */
    }

   

    /* Chat Popup */
    .chat-popup {
        display: none;
        position: fixed;
        bottom: 0;
        right: 0;
        width: 300px;
        max-height: 400px;
        background: white;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 1002; /* Ensure it's on top of other popups */
        overflow: hidden;
        border-radius: 5px;
        background: linear-gradient(white, #f9f9f9);
    }

    .chat-popup-header {
        background: #007bff;
        color: white;
        padding: 10px;
        font-size: 1.2em;
        text-align: center;
        cursor: pointer;
        border-bottom: 1px solid #ccc;
        position: relative;
    }

    .chat-popup-header .close-btn {
        position: absolute;
        top: -10px;
        right: 10px;
        cursor: pointer;
        font-size: 2.1em;
        color: red;
        background: none;
        border: none;
    }

    .chat-box {
        padding: 10px;
        height: 280px;
        overflow-y: auto;
        border-bottom: 1px solid #ccc;
    }

    .chat-message {
        margin-bottom: 10px;
    }

    .color-palette {
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }

    .color-box {
        width: 30px;
        height: 30px;
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 5px;
    }

    .selected {
        border-color: #000;
    }

    .countdown {
        font-size: 0.8em;
        color: gray;
    }

    .timestamp {
        font-size: 0.8em;
        color: #888;
    }

    @keyframes moveUpDown {
        0% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0); }
    }

    .logo {
        width: 120px;
        height: auto;
        display: block;
        margin: 0 auto;
        animation: moveUpDown 2s ease-in-out infinite;
        /* เพิ่มเอฟเฟกต์การเรืองแสง */
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));
        transition: filter 0.3s ease;
    }

    .logo:hover {
        filter: drop-shadow(0 0 20px rgba(255, 149, 203, 1)); /* เปลี่ยนสีเมื่อ hover */
    }

    /* Styling for ID, Role, and Welcome message with red text and pink glowing border */
    .user-info {
        color: red;
        padding: 5px;
        border-radius: 5px;
        box-shadow: 0 0 10px pink;
        display: block;
        margin: 10px 0;
    }

    /* Align ID, Role, and Welcome message */
    .user-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .user-section p {
        margin: 10px 0;
    }

    /* Popup for Online Admins */
    .popup {
        display: none;
        position: fixed;
        top: 20%;
        right: 20px;
        width: 250px;
        background: white;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        z-index: 1001;
        overflow: hidden;
        border-radius: 5px;
        background: linear-gradient(white, #f9f9f9); /* Light gradient background */
    }

    .popup-header {
        background: #007bff;
        color: white;
        padding: 10px;
        font-size: 1.2em;
        text-align: center;
    }

    .popup-close {
        position: absolute;
        top: 5px;
        right: 10px;
        color: red;
        font-size: 2.1em;
        cursor: pointer;
    }

    .online-users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .online-users-table th, .online-users-table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
    }

    .online-users-table th {
        background-color: #f4f4f4;
    }

    /* Styling for chat messages as chat bubbles */
    .popup-message {
        background-color: #f1f1f1; /* Background color of chat bubble */
        border: 1px solid #ddd; /* Border color of chat bubble */
        border-radius: 10px; /* Rounded corners */
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
        max-width: 90%;
        word-wrap: break-word;
    }

    .popup-message:before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 20px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #f1f1f1; /* Background color of chat bubble */
    }

    /* Container for chat input and send button */
    .chat-input-container {
        display: flex;
        align-items: center;
        border-top: 1px solid #ccc;
        padding: 5px;
    }

    #message {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
    }

    .send-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        font-weight: bold;
        margin-left: 10px;
        transition: background-color 0.3s;
    }

    .send-button:hover {
        background-color: #0056b3;
    }

    .send-button:active {
        background-color: #004494;
    }

    /* Styling for Action Buttons */
    .action-button {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        background-color: #007bff; /* สีพื้นหลังของปุ่ม */
        color: white; /* สีตัวหนังสือ */
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
        font-size: 1em;
        font-weight: bold;
    }

    .action-button:hover {
        background-color: #0056b3; /* สีปุ่มเมื่อ hover */
    }

    .action-button:active {
        background-color: #004494; /* สีปุ่มเมื่อคลิก */
    }

    /* Styling for the Logout Button */
    .logout-button {
        background-color: #dc3545; /* สีพื้นหลังของปุ่มออกจากระบบ */
    }

    .logout-button:hover {
        background-color: #c82333; /* สีปุ่มออกจากระบบเมื่อ hover */
    }

    .logout-button:active {
    background-color: #a71d2a; /* สีปุ่มออกจากระบบเมื่อคลิก */
    }

    /* Center the button container */
    .button-container {
    text-align: center;
    }

    .sakura {
        position: absolute;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .petal {
        position: absolute;
        background: url('../assets/images/petal.png'); /* ใช้รูปดอกซากุระ */
        background-size: contain;
        width: 20px;
        height: 20px;
        opacity: 0.8;
        animation: fall linear infinite;
    }

    @keyframes fall {
        0% {
            transform: translateY(0) rotate(0deg);
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
        }
    }

    /* Glow effect for welcome message */
    .welcome-message {
        color: #e94097;
        padding: 5px;
        border-radius: 5px;
        display: block;
        margin: 10px 0;
        font-size: 2em; /* Adjust size as needed */
        text-align: center; /* Center the text */
    }

    </style>
</head>
<body>
    <div class="sakura"></div> <!-- Added sakura div -->
    <div class="container">
        <img src="../assets/images/sakura_project.jpg" alt="Logo" class="logo">
        <h1 class="welcome-message">ยินดีต้อนรับสู่ Sakura Project</h1>
        <div class="button-container">
            <!-- User Info Section with separate rows for ID, Role, and Welcome message -->
            <div class="user-section">
                <p class="user-info">ยินดีต้อนรับ : <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                <p class="user-info">ไอดีผู้ใช้ : <?php echo $_SESSION['user_id']; ?></p>
                <p class="user-info">ยศ : <?php echo $_SESSION['role']; ?></p>
            </div>
            <a href="products.php" class="action-button">ดูสินค้า</a>
            <a href="logout.php" class="action-button logout-button">ออกจากระบบ</a>
            <a href="#" id="chat-button" class="chat-button">Chat</a>
            <a href="#" id="popup-button" class="popup-button">ดูแอดมินที่ออนไลน์</a>
            <a href="#" id="music-button" class="music-button"><i class="fas fa-play"></i> เล่นเพลง</a>
        </div>

        <audio id="background-music" loop>
            <source src="../assets/music/background.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <!-- Chat Popup -->
        <div id="chat-popup" class="chat-popup">
            <div class="chat-popup-header">
                Chat
                <button id="chat-popup-close" class="close-btn">&times;</button>
            </div>
            <div id="chat-box" class="chat-box"></div>
            <div class="chat-input-container">
                <textarea id="message" placeholder="Type your message..."></textarea>
                <button id="send-message" class="send-button">Send</button>
            </div>
        </div>

        <!-- Popup for Online Admins -->
        <div id="popup" class="popup">
            <span id="popup-close" class="popup-close">&times;</span>
            <div class="popup-header">ผู้ดูแลระบบออนไลน์</div>
            <div class="popup-content">
            <table class="online-users-table">
                <tr>
                    <th>Online Admins</th>
                </tr>
                <?php foreach ($online_users as $user): ?>
                <tr>
                    <td>
                        <?php echo '<i class="fas fa-crown" style="color: gold; margin-right: 5px;"></i>' . htmlspecialchars($user); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <p>ผู้ดูแลระบบที่ออนไลน์: <?php echo count($online_users); ?> คน</p>
        </div>
    </div>

     <!-- Footer for developer credit -->
     <div class="footer">
        <p>เว็บไซต์นี้พัฒนาโดย [ © Sarawut Dev ] - <a href="mailto:AppleN0234@gmail.com" style="color: #3366FF;">ติดต่อเรา</a></p>
    </div>


    <script src="../assets/js/chat.js"></script>
</body>
</html>
