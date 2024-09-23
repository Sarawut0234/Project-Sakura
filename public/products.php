<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll();
$product_count = count($products); // Count the number of products

// Handle delete product request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product_id'])) {
    $delete_product_id = $_POST['delete_product_id'];

    // ลบสินค้าจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$delete_product_id]);

    // เปลี่ยนเส้นทางไปยังหน้า products.php
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

        // Disable text selection
        document.addEventListener('selectstart', function(event) {
            event.preventDefault();
        });

        // Disable copy event
        document.addEventListener('copy', function(event) {
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

        .products-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            max-height: 80vh; /* Set max height for scrolling */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        .title-box {
            background: rgba(255, 255, 255, 0.8); /* Slightly transparent background */
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 1200px; /* Maximum width for the title box */
        }

        .title-box h1 {
            font-size: 24px;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            margin: 0;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
        }

        .product-box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 10px;
            padding: 15px;
            width: calc(30% - 20px);
            text-align: center;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .product-box img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .product-box img:hover {
            transform: scale(1.05);
        }

        .product-box h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .product-box p {
            margin: 5px 0;
            color: #666;
        }

        .product-box .price {
            color: #4CAF50;
            font-weight: bold;
            font-size: 16px;
        }

        .product-box .actions {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-box .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            margin: 5px 0;
            transition: background-color 0.3s, transform 0.3s;
        }

        .product-box .button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .button-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .button-box {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 25px;
            width: calc(50% - 20px);
            text-align: center;
            box-sizing: border-box;
            margin: 10px;
        }

        .button-box .button {
            width: calc(100% - 20px);
            box-sizing: border-box;
            margin: 10px 0;
        }

        .button-back {
            background-color: #f44336;
        }

        .button-add {
            background-color: #3366FF;
        }

        .button-orders {
            background-color: #00FF00;
            color: black;
        }

        .button-orders i {
            font-size: 24px;
            margin-right: 8px;
        }

        .button-delete {
            background-color: #d32f2f;
        }

        .button-back:hover {
            background-color: #d32f2f;
        }

        .button-add:hover {
            background-color: #1976D2;
        }

        .button-orders:hover {
            background-color: #339900;
        }

        @media (max-width: 768px) {
            .product-box, .button-box {
                width: calc(45% - 20px);
            }
        }

        @media (max-width: 480px) {
            .product-box, .button-box {
                width: calc(100% - 20px);
            }
        }
    </style>
</head>
<body>
    <div class="products-container">
        <div class="title-box">
            <h1>รายการสินค้า</h1>
        </div>
        <div class="product-container">
            <?php if ($product_count > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-box">
                        <a href="product_details.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        </a>
                        <h3><?php echo $product['name']; ?></h3>
                        <p class="price">฿<?php echo $product['price']; ?></p>
                        <p>จำนวน: <?php echo $product['quantity']; ?></p>
                        <div class="actions">
                            <form method="post" action="checkout.php" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="button">ซื้อ</button>
                            </form>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <form method="post" action="products.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="delete_product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="button-delete">ลบ ( Admin )</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>ไม่พบสินค้า.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="button-container">
        <div class="button-box">
            <a href="index.php" class="button button-back">กลับ</a>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="../admin/add_product.php" class="button button-add">เพิ่มสินค้า</a>
            <?php endif; ?>
            <a href="orders.php" class="button button-orders">คำสั่งซื้อ
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </div>
</body>
</html>



