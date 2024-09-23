document.addEventListener('DOMContentLoaded', function() {
    // Toggle Popup visibility
    document.getElementById('popup-button').addEventListener('click', function() {
        var popup = document.getElementById('popup');
        popup.style.display = popup.style.display === 'none' || popup.style.display === '' ? 'block' : 'none';
        if (popup.style.display === 'block') {
            loadOnlineAdmins(); // โหลดข้อมูลผู้ดูแลระบบออนไลน์เมื่อ popup เปิด
        }
    });

    document.getElementById('popup-close').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
    });

    // Toggle Chat Popup visibility
    document.getElementById('chat-button').addEventListener('click', function() {
        var chatPopup = document.getElementById('chat-popup');
        chatPopup.style.display = chatPopup.style.display === 'none' || chatPopup.style.display === '' ? 'block' : 'none';
        if (chatPopup.style.display === 'block') {
            loadChat(); // โหลดแชทเมื่อลง popup เปิด
            startChatPolling(); // เริ่ม polling แชท
        } else {
            stopChatPolling(); // หยุด polling เมื่อลง popup ปิด
        }
    });

    // Close chat popup
    document.getElementById('chat-popup-close').addEventListener('click', function() {
        document.getElementById('chat-popup').style.display = 'none';
        stopChatPolling(); // หยุด polling เมื่อปิด popup
    });

    // Event listener for sending chat message
    document.getElementById('send-message').addEventListener('click', function() {
        const messageInput = document.getElementById('message');
        const color = document.querySelector('.color-box.selected') ? document.querySelector('.color-box.selected').dataset.color : '#000';
        const message = messageInput.value;

        if (message.trim() !== '') {
            sendMessage(message, color);
            messageInput.value = ''; // Clear message input
        }
    });

    // Handle color palette
    document.querySelectorAll('.color-box').forEach(box => {
        box.addEventListener('click', function() {
            document.querySelectorAll('.color-box').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Function to load chat messages
    function loadChat() {
        fetch('chat.php')
            .then(response => response.json())
            .then(data => {
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = ''; // Clear previous messages
                data.messages.forEach(message => {
                    const chatMessage = document.createElement('div');
                    chatMessage.classList.add('chat-message');
                    chatMessage.style.color = message.color; // Use color from message
                    chatMessage.innerHTML = `<strong>${message.username}:</strong> ${message.text} <span class="timestamp">(${message.timestamp})</span>`;
                    chatBox.appendChild(chatMessage);
                });
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to bottom
            })
            .catch(error => console.error('Error loading chat:', error));
    }

    // Function to send a message
    function sendMessage(message, color) {
        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                message: message,
                color: color
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Optionally, you can refresh the chat immediately after sending a message
                loadChat();
            } else {
                console.error('Error sending message:', data.error);
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }

    // Function to load online admins
    function loadOnlineAdmins() {
        fetch('online_admins.php')
            .then(response => response.json())
            .then(data => {
                const popupContent = document.getElementById('popup-content');
                popupContent.innerHTML = ''; // ล้างเนื้อหาที่เคยมี
                if (data.online_users.length > 0) {
                    data.online_users.forEach(user => {
                        const userDiv = document.createElement('div');
                        userDiv.classList.add('popup-message');
                        userDiv.innerHTML = `<i class="fas fa-crown"></i> ${user.username}`;
                        popupContent.appendChild(userDiv);
                    });
                } else {
                    popupContent.innerHTML = '<p>No online admins at the moment.</p>';
                }
            })
            .catch(error => console.error('Error loading online admins:', error));
    }

    // Polling for chat updates
    let chatPollingInterval;

    function startChatPolling() {
        chatPollingInterval = setInterval(loadChat, 5000); // Update every 5 seconds
    }

    function stopChatPolling() {
        if (chatPollingInterval) {
            clearInterval(chatPollingInterval);
        }
    }
});
