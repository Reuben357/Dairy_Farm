<?php
require_once 'Cart.php';
require_once 'Product.php';

$cart = new Cart();
$product = new Product();
$products = $product->getAllProducts();
$total = $cart->getTotal($products);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = $_POST['phone_number'];

    // Implement M-Pesa API logic
    $payment_successful = true; // Simulate successful payment

    if ($payment_successful) {
        $cart->clearCart();
        $success_message = "Payment successful. Total: Ksh $total. Phone: $phone_number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <form method="POST" action="">
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number">
        <button type="submit">Pay</button>
    </form>
    <?php if (isset($success_message)) { echo $success_message; } ?>
</body>
</html>
