<?php
session_start();

// Calculate total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}

// Handle payment
if (isset($_POST['pay'])) {
    $phone_number = $_POST['phone_number'];
    
    // Here you would integrate with the M-Pesa API
    // For this example, we'll just simulate a successful payment
    
    $payment_successful = true;
    
    if ($payment_successful) {
        // Clear the cart
        unset($_SESSION['cart']);
        
        // Display success message
        $success_message = "Thank you for buying from us! A payment request of Ksh $total has been sent to $phone_number. Please complete the payment on your M-Pesa mobile app.";
    } else {
        $error_message = "Payment failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="/templates/css/checkout_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <section class="checkout container">
        <h2>Checkout</h2>
        <hr>

        <h3>Order Summary</h3>
        <table class="order-summary">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>Ksh <?php echo $item['price'] * $item['quantity']; ?>/=</td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>Ksh <?php echo $total; ?>/=</strong></td>
            </tr>
        </table>

        <?php
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        } elseif (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        } else {
            ?>
            <h3>Payment</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="phone_number">M-Pesa Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                </div>
                <input type="submit" class="pay-btn" value="Pay via M-Pesa" name="pay">
            </form>
            <?php
        }
        ?>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>