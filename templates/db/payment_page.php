<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total = $_POST['total'];
    $phone = $_POST['phone'];
    
    // Here you would integrate with the M-Pesa API to initiate the payment
    // For this example, we'll just simulate a successful payment
    
    // Simulate payment processing
    $payment_successful = true;
    
    if ($payment_successful) {
        // Clear the cart
        unset($_SESSION['cart']);
        
        echo "<h1>Payment Successful</h1>";
        echo "<p>Thank you for your purchase. A payment request of $${total} has been sent to ${phone}.</p>";
        echo "<p>Please complete the payment on your M-Pesa mobile app.</p>";
        echo "<p><a href='index.php'>Return to Homepage</a></p>";
    } else {
        echo "<h1>Payment Failed</h1>";
        echo "<p>There was an error processing your payment. Please try again.</p>";
        echo "<p><a href='cart.php'>Return to Cart</a></p>";
    }
} else {
    // Redirect to cart if accessed directly
    header("Location: cart.php");
    exit();
}
?>