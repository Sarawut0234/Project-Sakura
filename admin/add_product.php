<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $quantity = $_POST['quantity'];

    // เพิ่มสินค้าในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image_url, $quantity]);

    // เปลี่ยนเส้นทางไปยังหน้า products.php
    header("Location: ../public/products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-image: url('../assets/images/Sakura.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.8); /* ทำให้พื้นหลังของฟอร์มโปร่งแสง */
            padding: 20px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table th, .form-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .form-table th {
            background-color: #f2f2f2;
        }

        .form-table input,
        .form-table textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-table button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-table button i {
            margin-right: 8px; /* เว้นระยะห่างระหว่างไอคอนและข้อความ */
        }

        .form-table button:hover {
            background-color: #45a049;
        }

        .back-button {
            display: block;
            background-color: #dc3545; /* สีแดง */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px auto;
            width: fit-content;
            text-align: center;
        }

        .back-button:hover {
            background-color: #c82333; /* สีแดงเข้มเมื่อโฮเวอร์ */
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
        });

        // Disable right-click
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        // Disable drag event
        document.addEventListener('dragstart', function(event) {
            event.preventDefault();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>เพิ่มสินค้าใหม่</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="name">ชื่อสินค้า:</label></th>
                    <td><input type="text" id="name" name="name" required></td>
                </tr>
                <tr>
                    <th><label for="description">คำอธิบาย:</label></th>
                    <td><textarea id="description" name="description" required></textarea></td>
                </tr>
                <tr>
                    <th><label for="price">ราคา:</label></th>
                    <td><input type="number" id="price" name="price" required></td>
                </tr>
                <tr>
                    <th><label for="image_url">URL รูป:</label></th>
                    <td><input type="text" id="image_url" name="image_url" required></td>
                </tr>
                <tr>
                    <th><label for="quantity">จำนวน:</label></th>
                    <td><input type="number" id="quantity" name="quantity" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit"><i class="fas fa-plus"></i> เพิ่มสินค้า</button>
                    </td>
                </tr>
            </table>
        </form>
        <a href="../public/products.php" class="back-button">กลับ</a>
    </div>
</body>
</html>

