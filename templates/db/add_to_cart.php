<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check for session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: signin.php");
    exit();
}
$_SESSION['last_activity'] = time();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Fetch product details from the database
    $conn = getDbConnection();
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

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
            
            // Add the purchase to the purchases table
            $user_id = $_SESSION['user_id'];
            $purchase_date = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO purchases (user_id, product_id, product_name, product_price, product_image_url, quantity, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdsis", $user_id, $product_id, $product['name'], $product['price'], $product['image_url'], $quantity, $purchase_date);
            $stmt->execute();

            // If we've made it this far without an exception, commit the transaction
            $conn->commit();
            
            // Redirect back to the cart page
            header("Location: cart_page.php");
            exit();
        } else {
            // Product not found
            throw new Exception("Product not found");
        }
    } catch (Exception $e) {
        // An error occurred, rollback the transaction
        $conn->rollback();
        
        // Redirect to an error page
        header("Location: error_page.php");
        exit();
    } finally {
        $conn->close();
    }
} else {
    // If accessed directly without POST data, redirect to homepage
    header("Location: home_page.php");
    exit();
}
?>