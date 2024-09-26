<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add the product to the cart or update quantity if it already exists
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    // Redirect back to the product page or wherever you want
    header("Location: products_page.php?id=" . $product_id);
    exit();
} else {
    // If accessed directly without POST data, redirect to homepage
    header("Location: home_page.php");
    exit();
}
?>