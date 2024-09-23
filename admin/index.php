<?php
include '../includes/db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    die("Access denied!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="delete_product.php">Delete Product</a>
    </div>
</body>
</html>
