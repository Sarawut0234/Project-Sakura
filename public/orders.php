<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle delete order request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order_id'])) {
    $delete_order_id = $_POST['delete_order_id'];

    // ลบคำสั่งซื้อจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$delete_order_id, $user_id]);

    // เปลี่ยนเส้นทางไปยังหน้า my_orders.php
    header("Location: my_orders.php");
    exit();
}

// Fetch user orders from the database
$stmt = $conn->prepare("SELECT orders.id, products.name, products.price, products.image_url, orders.created_at FROM orders JOIN products ON orders.product_id = products.id WHERE orders.user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Calculate the total purchase amount
$total_amount = 0;
foreach ($orders as $order) {
    $total_amount += $order['price'];
}

// Count the number of orders
$order_count = count($orders);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/sakura_project.jpg">
    <style>
        body {
            background-image: url('../assets/images/Sakura.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .orders-wrapper {
            overflow-y: auto; /* Enables vertical scrolling */
            max-height: 80vh; /* Adjust height as needed */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .orders-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .order-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 300px;
            text-align: center;
            box-sizing: border-box;
        }

        .order-card img {
            max-width: 100%;
            border-radius: 10px;
        }

        .order-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .order-card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .order-card .button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .order-card .button:hover {
            background-color: #0056b3;
        }

        .order-card .delete-button {
            background-color: #dc3545;
        }

        .order-card .delete-button:hover {
            background-color: #c82333;
        }

        .back-button {
            display: block;
            background-color: #6c757d;
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
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .order-summary {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script type="text/javascript">
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

        // Disable drag event
        document.addEventListener('dragstart', function(event) {
            event.preventDefault();
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>คำสั่งซื้อทั้งหมด: <?php echo $order_count; ?></h2> <!-- Display the total number of orders -->
        </div>
        <div class="orders-wrapper">
            <div class="orders-container">
                <?php if ($order_count > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <img src="<?php echo $order['image_url']; ?>" alt="<?php echo $order['name']; ?>">
                            <h3><?php echo $order['name']; ?></h3>
                            <p>ID คำสั่งซื้อ: <?php echo $order['id']; ?></p>
                            <p>ราคา: ฿<?php echo $order['price']; ?></p>
                            <p>เวลาสั่งซื้อ: <?php echo $order['created_at']; ?></p>
                            <form method="post" action="my_orders.php" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                <input type="hidden" name="delete_order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="button delete-button">ยกเลิกคำสั่งซื้อ</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>ไม่พบคำสั่งซื้อ.</p>
                <?php endif; ?>
            </div>
            <div class="order-summary">
                <h3>ราคารวมทั้งหมด: ฿<?php echo $total_amount; ?></h3>
            </div>
        </div>
        <a href="products.php" class="back-button">กลับ</a>
    </div>
</body>
</html>



