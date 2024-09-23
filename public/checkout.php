<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $product_name = $product['name'];
        $product_price = $product['price'];
    } else {
        echo "Product not found!";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <style>
        body {
            background-image: url('../assets/images/Sakura.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            width: 80%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .product-box {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .product-box img {
            max-width: 150px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }

        .product-info {
            flex: 1;
        }

        .product-info h3 {
            margin: 0;
            color: #333;
        }

        .product-info p {
            margin: 5px 0;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 1em;
            text-align: center;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .button:hover {
            opacity: 0.9;
        }

        .button-confirm {
            background-color: #4CAF50; /* สีเขียว */
        }

        .button-confirm:hover {
            background-color: #45a049; /* สีเขียวเข้ม */
        }

        .button-back {
            background-color: #f44336; /* สีแดง */
        }

        .button-back:hover {
            background-color: #d32f2f; /* สีแดงเข้ม */
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
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
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>Checkout</h2>
        </div>
        <div class="product-box">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="product-info">
                <h3><?php echo $product_name; ?></h3>
                <p><strong>ราคา:</strong> ฿<?php echo $product_price; ?></p>
            </div>
        </div>
        <div class="button-container">
            <form method="post" action="process_order.php" style="display: inline;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit" class="button button-confirm">ยืนยันการซื้อ</button>
            </form>
            <a href="products.php" class="button button-back">กลับ</a>
        </div>
    </div>
</body>
</html>


