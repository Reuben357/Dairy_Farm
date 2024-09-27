<?php
require_once 'Cart.php';
require_once 'Product.php';

$cart = new Cart();
$product = new Product();
$products = $product->getAllProducts();

$total = $cart->getTotal($products);
$cartItems = $cart->getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>
    <section class="cart">
        <h2>Your Cart</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($cartItems as $id => $quantity) { ?>
                <tr>
                    <td><?php echo $products[$id]['name']; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $products[$id]['price'] * $quantity; ?></td>
                </tr>
            <?php } ?>
        </table>
        <h3>Total: Ksh <?php echo $total; ?></h3>
        <a href="checkout.php">Checkout</a>
    </section>
</body>
</html>
