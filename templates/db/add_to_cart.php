<?php
session_start();
require_once 'db.php'; // Make sure this file exists and contains the database connection logic

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Fetch product details from the database
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $conn->close();
    
    if ($product) {
        // Add the product to the cart or update quantity if it already exists
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => $quantity
            ];
        }
        
        // Redirect back to the product page or wherever you want
        header("Location: products_page.php?id=" . $product_id);
        exit();
    } else {
        // Product not found
        header("Location: home_page.php");
        exit();
    }
} else {
    // If accessed directly without POST data, redirect to homepage
    header("Location: home_page.php");
    exit();
}
?>