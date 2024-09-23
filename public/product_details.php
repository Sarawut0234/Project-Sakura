<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details from the database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// If product not found, redirect to products page
if (!$product) {
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    
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

    </script>

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

        .product-details-container {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            text-align: center;
        }

        .product-details-container img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-bottom: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .product-details-container h2 {
            margin: 10px 0;
            color: #333;
        }

        .product-details-container p {
            margin: 5px 0;
            color: #666;
        }

        .product-details-container .price {
            color: #4CAF50;
            font-weight: bold;
            font-size: 24px;
        }

        .product-details-container .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
        }

        .product-details-container .button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .button-back {
            background-color: #f44336 !important; /* สีแดง */
            color: white !important; /* สีตัวอักษรให้เป็นสีขาว */
        }

        .button-back:hover {
            background-color: #d32f2f !important; /* สีแดงเข้ม */
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -4px 8px rgba(0,0,0,0.1);
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .product-details-container img {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="product-details-container">
        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
        <h2><?php echo $product['name']; ?></h2>
        <p class="price">฿<?php echo $product['price']; ?></p>
        <p>คำอธิบาย: <?php echo $product['description']; ?></p>
        <p>จำนวน: <?php echo $product['quantity']; ?></p>
        <form method="post" action="checkout.php" style="display: inline;">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" class="button">ซื้อ</button>
        </form>
        <a href="products.php" class="button button-back">กลับไปยังรายการสินค้า</a>
    </div>

    <!-- Footer for developer credit -->
    <div class="footer">
        <p>เว็บไซต์นี้พัฒนาโดย [ © Sarawut Dev ] - <a href="mailto:AppleN0234@gmail.com" style="color: #3366FF;">ติดต่อเรา</a></p>
    </div>
</body>
</html>


